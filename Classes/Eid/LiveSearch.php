<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 	Ingo Renner <ingo@typo3.org>
 * 				Michael Knoll <knoll@punkt.de>, punkt.de GmbH
*
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This script handles live-search queries.
 * Use this URL for testing:
 *
 * http://pt_list_dev.centos.localhost/index.php?id=37&eID=tx_ptsolr_autocomplete&termLowercase=album
 */



/**
 * Outputs given json encoded response
 *
 * @param $TSFE initialized TSFE object
 * @param $response json encoded response string
 */
function sendResponse($TSFE, $response) {
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-cache, must-revalidate');
	header('Pragma: no-cache');
	header('Content-Length: ' . strlen($response));
	header('Content-Type: application/json; charset=' . $TSFE->renderCharset);
	header('Content-Transfer-Encoding: 8bit');
	echo $response;
}

// TSFE initialization
tslib_eidtools::connectDB();
$pageId     = filter_var(t3lib_div::_GET('id'), FILTER_SANITIZE_NUMBER_INT);
$languageId = filter_var(
	t3lib_div::_GET('L'),
	FILTER_VALIDATE_INT,
	array('options' => array('default' => 0, 'min_range' => 0))
);

$TSFE = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], $pageId, 0, TRUE);
$TSFE->initFEuser();
$TSFE->initUserGroups();
$TSFE->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
$TSFE->rootLine = $TSFE->sys_page->getRootLine($pageId, '');
$TSFE->initTemplate();
$TSFE->getConfigArray();
$TSFE->includeTCA();
$TSFE->sys_language_uid = $languageId;

$solrConfiguration = tx_solr_Util::getSolrConfiguration();

//--- --- --- --- Building Suggest Query --- --- --- ---

$site = tx_solr_Site::getSiteByPageId($pageId);
// TODO we have to do some quoting here!
#die(t3lib_div::_GP('termLowercase'));
$q    = strtolower(trim(t3lib_div::_GP('termLowercase')));

// if we have an empty searchword, we return empty array
if ($q == '') {
	sendResponse($TSFE, json_encode(array()));
}

#echo "Searchword before: " . $q;

if (!preg_match('/\s$/', $q)) {
	// we add * to searchword, if we do not have whitespace at the end
	$q .= '*';
}

// Set up solr query
$query = t3lib_div::makeInstance('tx_solr_query', $q);
$query->setUserAccessGroups(explode(',', $TSFE->gr_list));
//$query->setSiteHash($site->getSiteHash());

$language = 0;
if ($TSFE->sys_language_uid) {
	$language = $TSFE->sys_language_uid;
}
$query->addFilter('language:' . $language);

$query->setOmitHeader();

$additionalFilters = t3lib_div::_GET('filters');
if (!empty($additionalFilters)) {
	$additionalFilters = json_decode($additionalFilters);
	foreach ($additionalFilters as $additionalFilter) {
		$query->addFilter($additionalFilter);
	}
}

#--- --- --- --- Search --- --- --- ---

$solr   = t3lib_div::makeInstance('tx_solr_ConnectionManager')->getConnectionByPageId(
	$pageId,
	$languageId
);
$search = t3lib_div::makeInstance('tx_solr_Search', $solr); /* @var $search tx_solr_Search */

if ($search->ping()) {
	if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifySearchQuery'])) {
		foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifySearchQuery'] as $classReference) {
			$queryModifier = t3lib_div::getUserObj($classReference);

			if ($queryModifier instanceof tx_solr_QueryModifier) {
				$query = $queryModifier->modifyQuery($query);
			}
		}
	}

	// Change parameter if you want more than 4 results
	$results = json_decode($search->search($query, 0, 4)->getRawResponse());

	#echo "<pre>";
	#var_dump($results);
	#echo "</pre>";
	#die();

	$ajaxReturnData = json_encode($results);
} else {
	$ajaxReturnData = json_encode(array('status' => FALSE));
}

// Output response if everything is ok
sendResponse($TSFE, $ajaxReturnData);

?>