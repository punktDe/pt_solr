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

/**
 * Class implements controller for solr Debugging module
 *
 * @package Controller
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtSolr_Controller_DebugController extends Tx_PtExtlist_Controller_AbstractBackendListController {

	/**
	 * @var string relative path under settings of this extension to the extlist typoScript configuration
	 */
	protected $extlistTypoScriptSettingsPath = 'listConfigs.backendDebug';



	/**
	 * @var string the pagerIdentifier to use
	 */
	protected $pagerIdentifier = 'delta';



	/**
	 * @var string
	 */
	protected $filterboxIdentifier = 'debugFilterbox';



	/**
	 * Array of available exportTypeIdentifiers
	 *
	 * @var array
	 */
	protected $exportIdentifiers = array();



	/**
	 * Set up this controller with list identifier
	 */
	public function initializeAction() {
		parent::initializeAction();
	}



	protected function initListIdentifier() {
		$this->listIdentifier = 'backendDebug';
	}



    /**
     * Action renders an overview page for the backend module
     *
     * @return string Rendered overview page
     */
    public function indexAction() {
        $this->view->assign('listData', $this->extListContext->getRenderedListData());
    }

}