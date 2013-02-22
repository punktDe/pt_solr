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
 * Class implements solr list data object containing rows for a list.
 *
 * Solr adds some additional information to a list, therefore we extend pt_extlist's list data object.
 * 
 * @author Michael Knoll
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List
 */
class Tx_PtSolr_Extlist_Model_ListData extends Tx_PtExtlist_Domain_Model_List_ListData {

	/**
	 * Holds total item count of solr search
	 *
	 * @var int
	 */
	protected $totalItemCount;



	/**
	 * Holds actual search phrase
	 *
	 * @var string
	 */
	protected $searchWord;



	/**
	 * Holds the whole solr statistics array
	 *
	 * @var array
	 */
	protected $solrStatistics;



	/**
	 * Holds spell checking suggestion
	 *
	 * @var string
	 */
	protected $spellCheckingSuggestion;



	/**
	 * Setter for total item count (numFound in solr)
	 *
	 * @param int $totalItemCount
	 */
	public function setTotalItemCount($totalItemCount) {
		$this->totalItemCount = intval($totalItemCount);
	}



	/**
	 * Getter for total item count
	 *
	 * @return int
	 */
	public function getTotalItemCount() {
		return $this->totalItemCount;
	}



	/**
	 * Setter for searchword
	 *
	 * @param string $searchWord
	 */
	public function setSearchWord($searchWord) {
		$this->searchWord = $searchWord;
	}



	/**
	 * Getter for searchword
	 *
	 * @return string
	 */
	public function getSearchWord() {
		return $this->searchWord;
	}



	/**
	 * Setter for spell-checking suggestions
	 *
	 * @param $spellCheckingSuggestion
	 */
	public function setSpellCheckingSuggestion($spellCheckingSuggestion) {
		$this->spellCheckingSuggestion = $spellCheckingSuggestion;
	}



	/**
	 * Getter for spell checking suggestion
	 *
	 * @return array Spell checking suggestion
	 */
	public function getSpellCheckingSuggestion() {
		return $this->spellCheckingSuggestion;
	}



	/**
	 * Copy list data from another list data
	 *
	 * TODO we should introduce a "getNewEmptyInstance" method here, which does the job for us.
	 *
	 * When we use renderer chain to render list data, we create a new
	 * object after each part of the chain. The chain does not know anything
	 * about "special" data stored in this object, so we have to create a
	 * object-specific method to copy this data from the old list data to the new list data.
	 *
	 * @param Tx_PtSolr_Extlist_Model_ListData $listDataToCopyDataFrom
	 */
	public function copyListData(Tx_PtSolr_Extlist_Model_ListData $listDataToCopyDataFrom) {
		$this->setTotalItemCount($listDataToCopyDataFrom->getTotalItemCount());
		$this->setSearchWord($listDataToCopyDataFrom->getSearchWord());
		$this->setSpellCheckingSuggestion($listDataToCopyDataFrom->getSpellCheckingSuggestion());
	}

}
?>