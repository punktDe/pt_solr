<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * Class implements modifier that sets current limits in solr query
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
class Tx_PtSolr_Extlist_DataBackend_QueryModifier_LimitModifier extends Tx_PtSolr_Extlist_DataBackend_QueryModifier_AbstractQueryModifier {

    /**
     * Modifies given query due to functionality of current modifier
     *
     * This modifier sets keywords in given solr query. Keywords are the main
     * search words used for current solr query.
     *
     * @param tx_solr_Query $solrQuery
     * @return void
     */
    public function modifyQuery(tx_solr_Query $solrQuery) {
        $solrQuery->setPage($this->dataBackend->getCurrentPage());
        $solrQuery->setResultsPerPage($this->dataBackend->getItemsPerPage());
    }
    
}
?>