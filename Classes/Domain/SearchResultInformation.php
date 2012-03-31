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
 * Class implements result information for solr search.
 *
 * @package Domain
 * @author Michael Knoll
 */
class Tx_PtSolr_Domain_SearchResultInformation {

	/**
	 * Numbers of results found
	 *
	 * @var Tx_PtExtlistSolr_Domain_SolrDataBackend_DataBackend
	 */
	protected $solrDataBackend;



	/**
	 * Constructor injects solr data backend
	 *
	 * @param Tx_PtExtlistSolr_Domain_SolrDataBackend_DataBackend $solrDataBackend
	 */
	public function __construct(Tx_PtExtlistSolr_Domain_SolrDataBackend_DataBackend $solrDataBackend) {
		$this->solrDataBackend = $solrDataBackend;
	}



	/**
	 * Getter for current search phrase
	 *
	 * @return string
	 */
	public function getSearchPhrase() {
		return $this->solrDataBackend->getSearchWords();
	}



	/**
	 * Getter for total item count
	 *
	 * @return int
	 */
	public function getResultsCount() {
		return $this->solrDataBackend->getTotalItemsCount();
	}

}
?>