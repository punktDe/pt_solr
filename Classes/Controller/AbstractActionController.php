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
 * Base class for all action controllers in pt_solr
 *
 * @package Controller
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtSolr_Controller_AbstractActionController extends Tx_PtExtbase_Controller_AbstractActionController {

    /**
     * Holds list identifier of solr list configuration set in TS or FlexForm
     *
     * @var string
     */
    protected $solrExtlistIdentifier;



    /**
     * Holds extlist context for given solr configuration
     *
     * @var Tx_PtExtlist_ExtlistContext_ExtlistContext
     */
    protected $solrExtlistContext;



    /**
     * Initializes controller before invoking an action
     */
    protected function initializeAction() {
        parent::initializeAction();
        $this->determineSolrListIdentifier();
    }



    /**
     * Sets solr extlist identifier from flexform or uses 'solr' as default value
     */
    protected function determineSolrListIdentifier() {
        if (isset($this->settings['listIdentifier']) && $this->settings['listIdentifier'] !== '') {
            $listIdentifier = $this->settings['listIdentifier'];
        } else {
            $listIdentifier = 'solr';
        }
        $this->solrExtlistIdentifier = $listIdentifier;
    }

}
?>