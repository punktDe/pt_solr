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
 * Class implements controller for solr search word filter
 *
 * @package Controller
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtSolr_Controller_SearchWordFilterController extends Tx_PtSolr_Controller_AbstractActionController {

    /**
     * Action renders result list
     *
     * @return string Rendered result list action
     */
    public function showAction() {
        /**
		 * Nothing to do here since default components are assigned to view
		 * in abstract controller and only template is rendered
		 */
    }



    /**
     * Action renders submit action for search word filter
     *
     * @return string Rendered submit action
     */
    public function submitAction() {
        $this->solrExtlistContext->resetPagerCollection();
		// TODO prevent redirect here
		$this->redirect('show');
    }



	/**
	 * Resets the filters and the pagers
	 *
	 * @param string $fullyQualifiedFilterIdentifier Filter identifier of the form filterboxName.filterIdentifier, if given, only this filter is reset
	 */
	public function resetAction($fullyQualifiedFilterIdentifier = '') {
		$this->solrExtlistContext->resetPagerCollection();
		if ($fullyQualifiedFilterIdentifier === '') {
			// No filter identifier given --> reset all filters
			$this->solrExtlistContext->resetFilterboxCollection();
		} else {
			// Filter identifier given --> reset only this filter
			$this->solrExtlistContext->getFilterByFullFiltername($fullyQualifiedFilterIdentifier)->reset();
		}
		// TODO prevent redirect here
		$this->redirect('show');
	}

}
