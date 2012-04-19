<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
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

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}



// Configures FE plugins for this extension
Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'ResultList' => 'list',
        'SearchWordFilter' => 'show, submit',
        'BreadCrumbs' => 'show',
        'QuickSearch' => 'show, submit',
        'Pager' => 'show',
        'FacetFilter' => 'show, submit'
	),
    array(
        'ResultList' => 'list',
        'SearchWordFilter' => 'show, submit',
        'BreadCrumbs' => 'show',
        'QuickSearch' => 'show, submit',
        'Pager' => 'show',
        'FacetFilter' => 'show, submit'
    )
);



// registering the eID script for auto-complete feature
$TYPO3_CONF_VARS['FE']['eID_include']['tx_ptsolr_autocomplete'] = 'EXT:'.$_EXTKEY.'/Classes/Eid/AutoComplete.php';



// registering the eID script for live-search feature
$TYPO3_CONF_VARS['FE']['eID_include']['tx_ptsolr_livesearch'] = 'EXT:'.$_EXTKEY.'/Classes/Eid/LiveSearch.php';

?>