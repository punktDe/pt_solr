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
 * This script handles auto-complete queries for searchword completion.
 * Use this URL for testing:
 *
 * http://pt_list_dev.centos.localhost/index.php?id=37&eID=tx_ptsolr_autocomplete&termLowercase=album
 *
 * Parameters:
 *
 * 		termLowercase 		- Searchword to be used for auto-completion
 * 		filters				- additional filters (json encoded)
 *
 * Remind that for making autocomplete feature run in frontend, you have to include the following JS files:
 *
 * <script src="/fileadmin/html/Js/jquery.js"></script>
 * <script src="/fileadmin/html/Js/jquery-ui.js"></script>
 *
 * Additionally there has to be JS code on your page, activating and configuring autocomplete:
 *
 * <script type="text/javascript">
 *	var tx_solr_suggestUrl = 'index.php?id=47&eID=tx_ptsolr_autocomplete';
 *	jQuery(document).ready(function(){
 *
 *		jQuery('#solrAutoComplete').autocomplete({
 *			//appendTo: '#solrAutoComplete',
 *			delay: 500,
 *			minLength: 2,
 *			position: {
 *				collision: "none",
 *				offset: '0 0'
 *			},
 *			source: function(request, response) {
 *				jQuery.ajax({
 *					url: tx_solr_suggestUrl,
 *					dataType: 'json',
 *					data: {
 *						termLowercase: request.term.toLowerCase(),
 *						termOriginal: request.term
 *					},
 *					success: function(data) {
 *						var rs     = [],
 *							output = [];
 *
 *						jQuery.each(data, function(term, termIndex) {
 *							var unformatted_label = term + ' <span class="result_count">(' + data[term] + ')</span>';
 *							output.push({
 *								label : unformatted_label.replace(new RegExp('(?![^&;]+;)(?!<[^<>]*)(' +
 *											jQuery.ui.autocomplete.escapeRegex(request.term) +
 *											')(?![^<>]*>)(?![^&;]+;)', 'gi'), '<strong>$1</strong>'),
 *								value : term
 *							});
 *						});
 *
 *						response(output);
 *					}
 *				})
 *			},
 *			select: function(event, ui) {
 *				this.value = ui.item.value;
 *				jQuery(event.target).closest('form').submit();
 *			},
 *			open: function() {
 *				jQuery('#autosuggest').show();
 *				//jQuery('#autosuggest').css('visibility', 'visible');
 *			},
 *			close: function() {
 *				jQuery('#autosuggest').hide();
 *				//jQuery('#autosuggest').css('visibility', 'hidden');
 *			}
 *		});
 *	});
 * </script>
 */


/* TSFE initialization */

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



/* Building Suggest Query */

$site = tx_solr_Site::getSiteByPageId($pageId);
$q    = trim(t3lib_div::_GP('termLowercase'));

$suggestQuery = t3lib_div::makeInstance('tx_solr_SuggestQuery', $q);
$suggestQuery->setUserAccessGroups(explode(',', $TSFE->gr_list));
//$suggestQuery->setSiteHash($site->getSiteHash());

$language = 0;
if ($TSFE->sys_language_uid) {
	$language = $TSFE->sys_language_uid;
}
$suggestQuery->addFilter('language:' . $language);
$suggestQuery->setOmitHeader();

require_once t3lib_extMgm::extPath('pt_extlist_solr') . 'Classes/Domain/SolrDataBackend/QueryModifier/QueryModifierInterface.php';
require_once t3lib_extMgm::extPath('pt_extlist_solr') . 'Classes/Domain/SolrDataBackend/QueryModifier/AbstractQueryModifier.php';

$additionalFilters = t3lib_div::_GET('filters');
if (!empty($additionalFilters)) {
	$additionalFilters = json_decode($additionalFilters);
	foreach ($additionalFilters as $additionalFilter) {
		$suggestQuery->addFilter($additionalFilter);
	}
}



/* Search */

$solr   = t3lib_div::makeInstance('tx_solr_ConnectionManager')->getConnectionByPageId(
	$pageId,
	$languageId
);
$search = t3lib_div::makeInstance('tx_solr_Search', $solr);

if ($search->ping()) {
	if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifySearchQuery'])) {
		foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifySearchQuery'] as $classReference) {
			$queryModifier = t3lib_div::getUserObj($classReference);

			if ($queryModifier instanceof tx_solr_QueryModifier) {
				$suggestQuery = $queryModifier->modifyQuery($suggestQuery);
			}
		}
	}

	$results = json_decode($search->search($suggestQuery, 0, 0)->getRawResponse());
	$facetSuggestions = $results->facet_counts->facet_fields->{$solrConfiguration['suggest.']['suggestField']};
	$facetSuggestions = get_object_vars($facetSuggestions);

	$suggestions = array();
	foreach($facetSuggestions as $partialKeyword => $value){
		$suggestionKey = trim($suggestQuery->getKeywords() . ' ' . $partialKeyword);
		$suggestions[$suggestionKey] = $facetSuggestions[$partialKeyword];
	}

	$ajaxReturnData = json_encode($suggestions);
} else {
	$ajaxReturnData = json_encode(array('status' => FALSE));
}

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Length: ' . strlen($ajaxReturnData));
header('Content-Type: application/json; charset=' . $TSFE->renderCharset);
header('Content-Transfer-Encoding: 8bit');
echo $ajaxReturnData;

?>