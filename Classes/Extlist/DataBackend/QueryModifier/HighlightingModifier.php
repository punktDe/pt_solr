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
 * Class implements query modifier that modifies solr query to enable
 * highlighting of searchword in result.
 *
 * For further information on highlighting see http://wiki.apache.org/solr/HighlightingParameters
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package pt_solr
 * @subpackage Extlist\DataBackend\QueryModifier
 */
class Tx_PtSolr_Extlist_DataBackend_QueryModifier_HighlightingModifier
	extends Tx_PtSolr_Extlist_DataBackend_QueryModifier_AbstractQueryModifier {

	/**
	 * Holds TypoScript settings for highlighting
	 *
	 * @var array
	 */
	protected $typoScriptHlSettings;



	/**
	 * Holds array of query parameters
	 *
	 * @var array
	 */
	protected $queryParameters;



	/**
	 * Holds solr query to be modified
	 *
	 * @var tx_solr_Query
	 */
	protected $solrQuery;



	/**
	 * Modifies given query due to functionality of current modifier
	 *
	 * This modifier enables searchword highlighting in results.
	 *
	 * @param tx_solr_Query $solrQuery
	 * @return void
	 */
	public function modifyQuery(tx_solr_Query $solrQuery) {
		$this->solrQuery = $solrQuery;
		$backendSettings = $this->dataBackend->getDataBackendSettings();
		$this->typoScriptHlSettings = $backendSettings['highlighting'];

		if ($this->highlightingIsEnabled()) {
			$this->queryParameters = $this->typoScriptHlSettings['hl'];
			$this->setSimplePrePost();
			$this->setSearchWordIfEnabled();
			$this->setQueryParametersOnQuery();
		}

	}



	/**
	 * Sets gathered parameters for highlighting in solr query
	 */
	private function setQueryParametersOnQuery() {
		$this->solrQuery->addQueryParameter('hl', 'true');
		foreach($this->queryParameters as $key => $value) {
			$this->solrQuery->addQueryParameter('hl.' . $key, $value);
		}
	}



	/**
	 * Sets original search word for highlighting in solr, if enabled
	 */
	private function setSearchWordIfEnabled() {
		if ($this->typoScriptHlSettings['useOriginalSearchWordForHighlighting'] == '1') {
			$this->queryParameters['q'] = $this->dataBackend->getSearchWords();
		}
	}



	/**
	 * Sets pre and post wrapping of highlighted phrase in solr query
	 */
	private function setSimplePrePost() {
		if (!empty($this->typoScriptHlSettings['simple_pre'])) {
			$this->queryParameters['simple.pre'] = $this->typoScriptHlSettings['simple_pre'];
		}
		if (!empty($this->typoScriptHlSettings['simple_post'])) {
			$this->queryParameters['simple.post'] = $this->typoScriptHlSettings['simple_post'];
		}
	}



	/**
	 * Returns true, if highlighting is enabled in TypoScript
	 *
	 * @return bool
	 */
	private function highlightingIsEnabled() {
		return $this->dataBackend->highlightingIsEnabled();
	}

}
?>