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
 * Class implements data backend for connecting pt_extlist to solr server
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Extlist
 * @subpackage DataBackend
 */
class Tx_PtSolr_Extlist_DataBackend_SolrDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {

	/**
	 * If set to true, some additional debug information will be output
	 */
	const DEBUG = FALSE;



	/**
	 * Holds time required for searching solr
	 *
	 * @var int
	 */
	private static $solrSearchTime = 0;




	/**
	 * Is true, if solr server can be pinged (up and running)
	 *
	 * @var bool
	 */
	protected $solrCanBePinged = null;



	/**
     * Holds identifier of filterbox that contains searchword filter
     *
	 * @var string
	 */
	protected $searchWordFilterboxIdentifier;



	/**
     * Holds identifier of searchword filter
     *
	 * @var string
	 */
	protected $searchWordFilterIdentifier;



    /**
     * Holds instance of searchword filter
     *
     * @var Tx_PtExtlist_Domain_Model_Filter_FilterInterface
     */
    protected $searchWordFilter;



    /**
     * Holds instance of solr data source
     * 
     * @var Tx_PtSolr_Extlist_DataBackend_DataSource_SolrDataSource
     */
    protected $dataSource;



    /**
     * Holds an instance of query modifier chain used to modify solr queries generated by this backend
     * 
     * @var Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChain
     */
    protected $queryModifierChain;



    /**
     * Holds an instance of query modifier chain used to modify solr facet queries generated by this backend
     *
     * @var Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChain
     */
    protected $facetQueryModifierChain;



    /**
     * Holds instance of solr search
     *
     * Naming of this class is rather misleading. By search we talk about the
     * object that is filled with response data after we did a solr request. So
     * it should rather be called "response"
	 *
	 * ATTENTION: tx_solr_Search is implemented as singleton. Whenever we do a search, we have to
	 * make sure, that we store its results properly, as otherwise the next search will overwrite
	 * our results!
     *
     * @var tx_solr_Search
     */
    protected $search = null;



	/**
	 * @var Apache_Solr_Response
	 */
	protected $response = null;



    /**
     * Holds instance of solr query interpreter
     *
     * @var Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter
     */
    protected $queryInterpreter;



	/**
	 * Holds cached responses for solr facet queries
	 *
	 * @var array
	 */
	protected $facetCache = array();



	/**
	 * Holds solr data mapper
	 *
	 * @var Tx_PtExtlistSolr_Domain_SolrDataBackend_SolrMapper_SolrResponseMapper
	 */
	protected $dataMapper;



	/**
	 * Holds all facet query parameters for each registered filter
	 *
	 * @var array
	 */
	protected $facetQueryParameters = array();



	/*************************************************************************************
	 * Public methods
	 *************************************************************************************/

	/**
	 * Injects solr query interpreter
	 *
	 * @param Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter $queryInterpreter
	 */
	public function injectSolrQueryInterpreter(Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter $queryInterpreter) {
		$this->queryInterpreter = $queryInterpreter;
	}



	/**
	 * Dummy injector for query interpreter
	 *
	 * @param $queryInterpreter
	 */
	public function _injectQueryInterpreter($queryInterpreter) {
		// Nothing to do here, since this method would overwrite queryInterpreter set by injectSolrQueryInterpreter
		// TODO remove this method, once DI issues in pt_extlist have been resolved
	}



	/*************************************************************************************
	 * Public methods
	 *************************************************************************************/

	/**
	 * Returns the value of the searchwordFilter
	 *
	 * @throws Exception
	 * @return string
	 */
	public function getSearchWords() {
		$searchWordFilterValue = $this->searchWordFilter->getValue();

		if ($this->doWeSearchOnEmptySubmit()) {
			if($searchWordFilterValue == '') $searchWordFilterValue = '*';
		}

		return $searchWordFilterValue;
	}



	/**
	 * Returns raw data for all filters excluding given filters.
	 *
	 * Result is given as associative array with fields given in query object.
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery Query that defines which group data to get
	 * @param array $excludeFilters List of filters to be excluded from query (<filterboxIdentifier>.<filterIdentifier>)
	 * @return array Array of group data with given fields as array keys
	 */
	public function getGroupData(Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery, $excludeFilters = array()) {
		throw new Exception('No group data can be generated when using solr backend! 1320470884');
	}



	/**
	 * Returns facet data for given facet parameters
	 *
	 * @param array $facetQueryParameters
	 * @param array $excludeFilters
	 * @return object Facet data object
	 */
	public function getFacetData(array $facetQueryParameters, $excludeFilters = array()) {

		// calculate hash for facet for caching
		$facetQueryParametersHash = md5(serialize($facetQueryParameters));

		if (array_key_exists($facetQueryParametersHash, $this->facetCache)) {  // we have facet already cached
			$result = $this->facetCache[$facetQueryParametersHash];
		} else { // we don't have facet cached
			$this->checkForSolrServer();
			// TODO Add paramater whether to respect searchword and other filter queries or not
			// TODO Add parameter whether to respect other filters or not
			// TODO Use parameter object that is passed to modifier chain to enable further settings
			$facetSearchWord = $this->getSearchPhraseForFacetSearch();
			$facetQuery = new Tx_PtExtlistSolr_Domain_Model_Query($facetSearchWord);

			$this->facetQueryModifierChain->modifyQuery($facetQuery);

			$facetQuery->setFaceting(true);
			$facetQuery->setQueryParametersByParametersArray($facetQueryParameters);

			$timeBefore = microtime(true);
			$facetResponse = $this->dataSource->search($facetQuery);
			$timeAfter = microtime(true);
			self::$solrSearchTime += ($timeAfter - $timeBefore);

			if (self::DEBUG) {
				echo "required search time: " . self::$solrSearchTime;
			}

			// TODO: solrObject can be accessed via array-syntax - @see http://www.php.net/manual/en/class.solrobject.php
			$result = $this->getFacetCounts($facetResponse)->facet_fields;

			$this->facetCache[$facetQueryParametersHash] = $result;

			#echo "Result of facet query: <pre>";
			#var_dump($facetQueryParameters);
			#var_dump($result);
			#echo "</pre>";

		}

		return $result;

	}




	protected function buildFacetData() {

		/**
		 * Idee hier:
		 *
		 * 1. Jeder Filter hat 0 .. 1 faceting Parameter
		 * 2. Für jede FilterBox und für jeden Filter werden faceting Parameter gesammelt
		 * 3. Es wird EINE query zusammengebaut, die alle Facetten auf einmal abfragt
		 * 4. Der Namespace für die Facetten wird folgendermaßen zusammengebaut:
		 *
		 *    facet.field={!key="<filterboxIdentifier>___<filterIdentifier>"}<facetField>&f.<filterboxIdentifier>___<filterIdentifier>.facet.<methodName>=<facetMethodValue>
		 *
		 *    Beispiel:
		 *
		 *    facet.field={!key="filterbox1___filter1"}type&f.filterbox1___filter1.facet.sort=true
		 *
		 *    Laut https://issues.apache.org/jira/browse/SOLR-1351 funktioniert diese Lösung derzeit nicht!
		 *
		 *    Es können keine weitere Funktionen an die Facette gehängt werden, wenn diese einen Alias bekommen hat. Es können aber
		 *
		 *
		 *  Konkretes Beispiel für das Problem:
		 *
		 * Das hier geht:
		 *
		 * http://devel.intern.punkt.de:8080/solr/dev-Ikomsys2-1-0-de_DE/select?indent=on&version=2.2&q=*%3A*&fq=&start=0&rows=10&fl=*%2Cscore&qt=&wt=&explainOther=&hl.fl=&facet.field={!key=%22filterbox1_filter1%22}type&facet=on&facet.field={!key=%22filterbox1_filter2%22}pid&f.type.facet.mincount=1000
		 *
		 *
		 * Das hier geht nicht:
		 *
		 * http://devel.intern.punkt.de:8080/solr/dev-Ikomsys2-1-0-de_DE/select?indent=on&version=2.2&q=*%3A*&fq=&start=0&rows=10&fl=*%2Cscore&qt=&wt=&explainOther=&hl.fl=&facet.field={!key=%22filterbox1_filter1%22}type&facet=on&facet.field={!key=%22filterbox1_filter2%22}pid&f.filterbox1_filter2.facet.mincount=1000
		 *
		 * ACHTUNG: Das Problem tritt nur auf, wenn EIN FELD mit unterschiedlichen FACET PARAMETERN angefragt werden soll.
		 * Solange jedes Feld nur einmal angefragt wird, ist das kein Problem, siehe hier:
		 *
		 * https://github.com/gsf/node-solr/pull/10
		 *
		 * http://localhost:8983/solr/select?q=ipod&rows=0&facet=true&facet.limit=-1&facet.field=cat&facet.field=inStock
		 *
		 * Andere Lösung:
		 *
		 * Es wird in einer globalen Konfiguration festgelegt, wie die Facetten konfiguriert sein sollen (mincount...)
		 * Danach wird für jeden Filter die jeweilige Facette abgefragt und gespeichert --> cache
		 *
		 */

		// Give each facet an individual key:
		// facet.field={!ex=dt key=mylabel}doctype

		// facet.field={!key="filterbox1.filter1"}type&facet=on&facet.field={!key="filterbox1.filter2"}pid

		$facetSearchWord = $this->getSearchPhraseForFacetSearch();
		$facetQuery = new Tx_PtExtlistSolr_Domain_Model_Query($facetSearchWord);

		$facetQuery->setFaceting(TRUE);
		foreach ($this->facetQueryParameters as $fullQualifiedFilterIdentifier => $facetParams) {

			// $facetQuery->setQueryParametersByParametersArray()
		}

	}



	/**
	 * Checks whether to use current search phrase for facets or not
	 *
	 * @return string
	 */
	protected function getSearchPhraseForFacetSearch() {
		#echo "Backend settings <pre>";
		#var_dump($this->backendConfiguration->getSettings());
		#var_dump($this->backendConfiguration->getSettings('respectSearchwordOnFacetQuery'));
		#var_dump($this->backendConfiguration->getSettings('emptyFacetsOnEmptySearch'));
		#echo "</pre>";

		if ($this->backendConfiguration->getSettings('respectSearchwordOnFacetQuery') === "1") {
			if ($this->getSearchWords() !== '') { // Search word should be respected for facets and we have searchword which is not empty
				return $this->getSearchWords();
			} elseif ($this->backendConfiguration->getSettings('emptyFacetsOnEmptySearch') === "1") { // We have no searchword and don't want to have empty 'match-all'-query. So build 'match-nothing'-query
				// TODO think of something better to prevent search results in facets see Solr Book page 108 -field:[* TO *]
				return '*:abc';
			} else { // We have no searchword and want to match everything
				return '*:*';
			}
		} else { // We don't want to respect searchword and want to match everything
			return '*:*';
		}

	}



	/**
	 * Returns the number of items for current settings without pager settings
	 *
	 * @return int Total number of items for current data set
	 */
	public function getTotalItemsCount() {
		if ($this->response === null){
			$this->doSearch();
		}
		return $this->response->response->numFound;
	}



	/**
	 * Return an aggregate for a field and with a method defined in the given config
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateDataConfig
	 */
	public function getAggregatesByConfigCollection(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateDataConfigCollection) {
		throw new Exception("No group data can be generated by SOLR backend! 1320340798");
	}



	/**
	 * Returns current page from associated pager
	 *
	 * @return int
	 */
	public function getCurrentPage() {
		return $this->pagerCollection->getCurrentPage();
	}



	/**
	 * Returns items per page set by current pager
	 *
	 * @return int
	 */
	public function getItemsPerPage() {
		return $this->pagerCollection->getItemsPerPage();
	}



	/**
	 * Returns TS data backend settings
	 *
	 * @return mixed
	 */
	public function getDataBackendSettings() {
		return $this->backendConfiguration->getDataBackendSettings();
	}



	/**
	 * Getter for searchword filterbox identifier
	 *
	 * @return string Identifier for searchword filterbox registerd at this backend
	 */
	public function getSearchwordFilterboxIdentifier() {
		return $this->searchWordFilterboxIdentifier;
	}



	/**
	 * Getter for searchword filter identifier
	 *
	 * @return string Identifier of searchword filter registered at this backend
	 */
	public function getSearchwordFilterIdentifier() {
		return $this->searchWordFilterIdentifier;
	}



	/**
	 * Getter for searchword filter
	 *
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	public function getSearchwordFilter() {
		return $this->searchWordFilter;
	}



	/**
	 * Getter for current query interpreter
	 *
	 * @return Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter
	 */
	public function getQueryInterpreter() {
		return $this->queryInterpreter;
	}



	/*************************************************************************************
	 * Initialization
	 *************************************************************************************/

    /**
	 * Init the configuration for this data backend
	 */
	protected function initBackendByTsConfig() {
        $this->setUpSearchWordFilter();
        $this->setUpSolrEnvironment();
        $this->setUpQueryModifierChain();
        $this->setUpFacetQueryModifierChain();
		$this->setUpFacetQueries();
		if (self::DEBUG) {
			echo "<pre>";
			var_dump($this->facetQueryParameters);
			echo "</pre>";
		}
	}



	/**
	 * Sets up searchword filter for given TS configuration
	 *
	 * @throws Exception
	 * @return void
	 */
	protected function setUpSearchWordFilter() {
		list($this->searchWordFilterboxIdentifier, $this->searchWordFilterIdentifier) = explode('.', $this->backendConfiguration->getDataBackendSettings('searchWordFilter'));

		if(!$this->searchWordFilterboxIdentifier || !$this->searchWordFilterIdentifier) {
			throw new Exception("Either searchWordFilterboxIdentifier or searchWordFilterIdentifier not set in your TS setup! 1319804606");
		}

		if (!$this->filterboxCollection->hasItem($this->searchWordFilterboxIdentifier)) {
			throw new Exception('You set up a filterbox identifier that should contain searchword filter which is actually not available in your list configuration! 1320326890');
		}

		$searchWordFilterbox = $this->filterboxCollection->getFilterboxByFilterboxIdentifier($this->searchWordFilterboxIdentifier);

		if (!$searchWordFilterbox->hasItem($this->searchWordFilterIdentifier)) {
			throw new Exception('You set up a filter identifier for your searchword that should be contained in ' . $this->searchWordFilterboxIdentifier . ' filterbox. But this filterbox does not contain this filter! 1320327008');
		}

		$this->searchWordFilter = $searchWordFilterbox->getFilterByFilterIdentifier($this->searchWordFilterIdentifier);
	}



	/**
	 * Sets up solr environment for data backend
	 *
	 * @return void
	 */
	protected function setUpSolrEnvironment() {
		$solrConnectionManager = new tx_solr_ConnectionManager();
		$connectionManager = new Tx_PtSolr_Extlist_DataBackend_DataSource_ConnectionManager($solrConnectionManager);
		$this->dataSource = new Tx_PtSolr_Extlist_DataBackend_DataSource_SolrDataSource($connectionManager);
	}



	/**
	 * Sets up query modifier chain used to modify solr queries generated by this backend
	 *
	 * @return void
	 */
	protected function setUpQueryModifierChain() {
		$this->queryModifierChain = Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChainFactory::getInstance($this, $this->backendConfiguration->getDataBackendSettings('queryModifierChain'));;
	}



	/**
	 * Sets up facet query modifier chain used to modify solr facet queries generated by this backend
	 *
	 * @return void
	 */
	protected function setUpFacetQueryModifierChain() {
		$this->facetQueryModifierChain = Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChainFactory::getInstance($this, $this->backendConfiguration->getDataBackendSettings('facetQueryModifierChain'));
	}



	/**
	 * Gathers facet query settings from all attached filters (if they have facet query settings)
	 */
	protected function setUpFacetQueries() {
		foreach($this->filterboxCollection as $filterbox) { /* @var $filterbox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
			foreach($filterbox as $filter) { /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
				$filterSettings = $filter->getFilterConfig()->getSettings();
				if (array_key_exists('facetQueryParameters', $filterSettings)) {
					$this->facetQueryParameters[$filterbox->getFilterboxIdentifier() . '.' . $filter->getFilterIdentifier()] = $filterSettings['facetQueryParameters'];
				}
			}
		}
	}



    /**
     * Initializes solr data backend
     * 
     * @return void
     */
    protected function initBackend() {
		$this->pagerCollection->setItemCount(PHP_INT_MAX);
    }



	/*************************************************************************************
	 * Building list data
	 *************************************************************************************/

    /**
     * Build the listData and cache it in $this->listData
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData Generated list data
     */
    protected function buildListData() {
		// Check whether we have empty searchword and want to do solr query then.
		if ($this->getSearchWords() === '' && !$this->doWeSearchOnEmptySubmit()) {
			return new Tx_PtExtlistSolr_Domain_Model_List_ListData();
		}

        if ($this->response === null) {
            $this->doSearch();
        }

        $responseDocuments =  $this->response->response->docs;

		// TODO: return solr query for debugging (this can be done in tx_solr_Service class)
		Tx_PtExtbase_Assertions_Assert::isArray($responseDocuments, array('message' => 'solr request did not return an array. Seems like there is an error in your solr query! 1326296106'));

		try {
			$listData = $this->dataMapper->getMappedListData($responseDocuments); /* @var $listData Tx_PtExtlistSolr_Domain_Model_List_ListData */
			$listData->setTotalItemCount($this->response->response->numFound);
			$listData->setSearchWord($this->searchWordFilter->getValue());

			// Spell checking is some nested array...
			$spellCheckingSuggestions = $this->getSpellcheckingSuggestions();
			$listData->setSpellCheckingSuggestion($spellCheckingSuggestions['collation']);

        	return $listData;
		} catch(Exception $e) {
			// TODO: what could we do here!
			throw new Exception('SOLR query threw an exception! 1326296106');
		}

    }



	/*************************************************************************************
	 * Solr communication
	 *************************************************************************************/

	/**
	 * Throws an exception, if solr server cannot be pinged
	 *
	 * Solr can be pinged, if server is up and running.
	 *
	 * @throws Exception If server cannot be pinged.
	 */
	protected function checkForSolrServer() {
		if ($this->solrCanBePinged === null) {
			$this->solrCanBePinged = $this->dataSource->getSearchObject()->ping();
		}
		if (!$this->solrCanBePinged) {
			throw new Exception('Solr server cannot be pinged. Server seems to be down or not correctly configured. 1324568030');
		}
	}



    /**
     * Runs actual solr query
     *
     * @return void
     */
    protected function doSearch() {
		$this->checkForSolrServer();
        $query = $this->buildQuery();
        // TODO this is a workaround here, as page and results per page in $query are not respected
        $offset = ($query->getPage() - 1) * $query->getResultsPerPage();

        $limit = $query->getResultsPerPage();

		if (self::DEBUG) {
			echo "Settings for doSearch in solr DataBackend:<pre>";
			var_dump(
				array(
					'queryString' => $query->getQueryString(),
					'offset' => $offset,
					'limit' => $limit,
					'queryParameters' => $query->getQueryParameters(),
					'sorting' => $query->getSortingFields()
				)
			);
			echo "</pre>";
		}

		$beforeTime = microtime(true);
        $this->response = $this->dataSource->search($query, $offset, $limit);
		$afterTime = microtime(true);
		self::$solrSearchTime += ($afterTime - $beforeTime);

		if (self::DEBUG) {
			echo "required search time: " . self::$solrSearchTime;
		}

		#echo "this->response nach doSearch: <pre>";
		#var_dump($this->response);
		#echo "</pre>";
    }



	/**
	 * Returns true, if we search on empty searchword value
	 *
	 * @return bool
	 */
	protected function doWeSearchOnEmptySubmit() {
		$searchOnEmptySearchWord = $this->backendConfiguration->getDataBackendSettings('searchOnEmptySearchWord');
		if ($searchOnEmptySearchWord === '1') {
			return true;
		} else {
			return false;
		}
	}




	/*************************************************************************************
	 * solr query logic
	 *************************************************************************************/

	/**
	 * Builds solr query for current request
	 *
	 * @return tx_solr_Query
	 */
	protected function buildQuery() {
		$query = new tx_solr_Query(''); // keywords are set in modifier chain - not here!

        // TODO Use parameter object that is passed to modifier chain to enable further settings
        $this->queryModifierChain->modifyQuery($query);

		// TODO make spell checking configurable in TS!
		$query->setSpellchecking(true);

		// TODO put this into query modifier chain
		if ($this->backendConfiguration->getSettings('sorting') !== array()) {
			$query->setSorting($this->backendConfiguration->getSettings('sorting'));
		}

        return $query;
	}



	/**
	 * Returns spell checking suggestions or null, if none are available
	 *
	 * We have copied this method from tx_solr_Search to prevent usage of this ugly singleton
	 *
	 * @return array|bool
	 */
	public function getSpellcheckingSuggestions() {
		$spellcheckingSuggestions = FALSE;

		$suggestions = (array) $this->response->spellcheck->suggestions;
		if (!empty($suggestions)) {
			$spellcheckingSuggestions = $suggestions;
		}

		return $spellcheckingSuggestions;
	}



	/**
	 * Returns array of facet count for current response
	 *
	 * We have copied this method from tx_solr_Search to prevent usage of this ugly singleton
	 *
	 * @param Apache_Solr_Response
	 * @return mixed
	 * @throws UnexpectedValueException
	 */
	public function getFacetCounts($response) {
		static $facetCountsModified = FALSE;
		static $facetCounts         = NULL;

		$unmodifiedFacetCounts = $response->facet_counts;

		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifyFacets'])) {

			if (!$facetCountsModified) {
				$facetCounts = $unmodifiedFacetCounts;

				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifyFacets'] as $classReference) {
					$facetsModifier = t3lib_div::getUserObj($classReference);

					if ($facetsModifier instanceof tx_solr_FacetsModifier) {
						$facetCounts = $facetsModifier->modifyFacets($facetCounts);
						$facetCountsModified = TRUE;
					} else {
						throw new UnexpectedValueException(
							get_class($facetsModifier) . ' must implement interface tx_solr_FacetsModifier',
							1310387526
						);
					}
				}
			}

		} else {
			$facetCounts = $unmodifiedFacetCounts;
		}

		return $facetCounts;
	}

}
?>