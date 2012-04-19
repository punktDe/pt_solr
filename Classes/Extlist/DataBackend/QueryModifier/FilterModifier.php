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
 * Class implements modifier that sets filtering of current filters in query
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
class Tx_PtSolr_Extlist_DataBackend_QueryModifier_FilterModifier extends Tx_PtSolr_Extlist_DataBackend_QueryModifier_AbstractQueryModifier {

    /**
     * Modifies given query due to functionality of current modifier
     *
     * This modifier sets filter strings of registered filters in given query.
     *
     * @param tx_solr_Query $solrQuery
     * @return void
     */
    public function modifyQuery(tx_solr_Query $solrQuery) {
        $this->setAllFilterCriteriasOnSolrQuery($solrQuery);
    }



	/**
	 * Sets filter criterias from all filters registered in data backend in given solr query
	 *
	 * @param $solrQuery
	 */
    protected function setAllFilterCriteriasOnSolrQuery($solrQuery) {
        foreach ($this->dataBackend->getFilterboxCollection() as $filterbox) { /* @var $filterBox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
            $this->setAllFilterCriteriasFromFilterboxOnSolrQuery($solrQuery, $filterbox);
        }
    }



	/**
	 * Sets filter criterias from a given filterbox on given solr query
	 *
	 * @param tx_solr_Query $solrQuery
	 * @param Tx_PtExtlist_Domain_Model_Filter_Filterbox $filterbox
	 */
    protected function setAllFilterCriteriasFromFilterboxOnSolrQuery(tx_solr_Query $solrQuery, Tx_PtExtlist_Domain_Model_Filter_Filterbox $filterbox) {
        foreach ($filterbox as $filter) { /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
            if ($this->filterIsNotSearchwordFilterOfDataBackend($filter) && $this->filterIsNotToBeIgnored($filter)) {
                $translatedFilterString = $this->dataBackend->getQueryInterpreter()->translateCriterias($filter->getFilterQuery()->getCriterias());
                $solrQuery->addFilter($translatedFilterString);
            }
        }
    }



    /**
     * Returns true, if given filter is NOT searchword filter of current data backend
     *
     * @param Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter
     * @return bool
     */
    protected function filterIsNotSearchwordFilterOfDataBackend(Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter) {
        return ($filter->getFilterIdentifier() !== $this->dataBackend->getSearchwordFilterIdentifier()
                && $filter->getFilterBoxIdentifier() !== $this->dataBackend->getSearchwordFilterboxIdentifier());
    }



	/**
	 * Returns true, if current filter is NOT to be ignored
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter
	 * @return bool
	 */
	protected function filterIsNotToBeIgnored(Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter) {
		$fullQualifiedFilterName = $filter->getFilterBoxIdentifier() . '.' . $filter->getFilterIdentifier();
		return !(in_array($fullQualifiedFilterName, $this->dataBackend->getFiltersToBeIgnored()));
	}
    
}
?>