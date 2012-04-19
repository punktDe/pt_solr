<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
 *
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
 * Interface for query modifiers for solr data backend.
 *
 * Modifiers can be chained to modify solr query due to certain responsibilities.
 *
 * @author Michael Knoll
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
interface Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface {

    /**
     * Injector for solr data backend
     *
     * @abstract
     * @param Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $solrDataBackend
     * @return void
     */
    public function injectDataBackend(Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $solrDataBackend);



    /**
     * Modifies given query due to functionality of current modifier
     *
     * @abstract
     * @param tx_solr_Query $solrQuery
     * @return void
     */
    public function modifyQuery(tx_solr_Query $solrQuery);

}
?>