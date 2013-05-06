<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
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
 * Configuration file for pt_solr extension
 *
 * @author Michael Knoll
 */
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// Register plugin in available plugins
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Solr'
);



/**
 *  Register the Backend Modules for this Extension
 */
if (TYPO3_MODE === 'BE') {

	// Register the installation tool
	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'tools', // Make module a submodule of 'tools'
		'tx_solr_m1', // Submodule key
		'0', // Position
		array( // An array holding the controller-action-combinations that are accessible
			'Installation' => 'index'
		),
		array(
			'access' => 'user,group',
			'icon' => 'EXT:pt_solr/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod_installation.xml',
		)
	);


	// Register the debugging tool
	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'tools', // Make module a submodule of 'tools'
		'tx_solr_m2', // Submodule key
		'0', // Position
		array( // An array holding the controller-action-combinations that are accessible
			'Debug' => 'index'
		),
		array(
			'access' => 'user,group',
			'icon' => 'EXT:pt_solr/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod_debug.xml',
		)
	);
}



$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/FlexForm.xml');
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

// Make static TypoScript template available in includes
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', '[pt_solr] Solr');

