<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
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
 * Class implements a pt_extlist data provider for solr facet data.
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package Extlist
 * @subpackage DataBackend
 * @see Tx_PtSolr_Tests_Unit_Extlist_DataBackend_DataProvider_FacetDataProviderTest
 */
class Tx_PtSolr_Extlist_DataBackend_DataProvider_FacetDataProvider extends Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider {

	/**
	* Holds a reference to solr dataBackend
	*
	* @var Tx_PtSolr_Extlist_DataBackend_SolrDataBackend
	*/
	protected $dataBackend;



	/**
	 * Holds facet query from TS configuration
	 *
	 * @var string
	 */
	protected $facetQueryParameters = array();



	/**
	 * Array of filters to be excluded if options for this filter are determined
	 *
	 * @var array
	 */
	protected $excludeFilters = NULL;



	/**
	 * Init the data provider
	 */
	public function init() {
		$this->initDataProviderByTsConfig($this->filterConfig->getSettings());
	}



	/**
	 * Return the rendered filteroptions
	 *
	 * @return array filter options
	 */
	public function getRenderedOptions() {
        // TODO make this compatible with multiple facet fields
		$queryResult = $this->dataBackend->getFacetData($this->facetQueryParameters, $this->buildExcludeFiltersArray());
        $facetField = $this->facetQueryParameters['facet.field'];
        $queryResultForFacetField = (array)$queryResult->$facetField;
        # var_dump($queryResultForFacetField);

        $renderedOptions = array();
        foreach ($queryResultForFacetField as $key => $count) {
            $renderedOptions[$key] = array(
				# TODO check whether there should be field identifier instead of key here. FieldIdentifier can be collection!
				# so this won't work:
				#$this->filterConfig->getFieldIdentifier() => $key,
                'key' => $key,
                'rowCount' => $count,
                'value' => $key,
                'selected' => false
            );
			if ($this->filterConfig->getShowRowCount()) {
				$renderedOptions[$key]['value'] .= " ($count)";
			}
        }
        # var_dump($renderedOptions);
		return $renderedOptions;
	}



	/**
	 * Init the dataProvider by TS-conifg
	 *
	 * @param array $filterSettings
	 * @throws Exception if no facet configuration is set in filter configuration
	 */
	protected function initDataProviderByTsConfig($filterSettings) {
		// Set facet query for filter
        if (array_key_exists('facetQueryParameters', $filterSettings)) {
            // TODO we have to do some renaming of the parameters here to enable more than one facet
            $this->facetQueryParameters = $this->addFacetPrefixToTsKeys($filterSettings['facetQueryParameters']);
        } else {
            throw new Exception('Cannot use facetFilterProvider with a filter that does not have set "facetQueryParameters" in its setup! 1320441373');
        }

        // Set exclude filters for filter
        if (array_key_exists('excludeFilters', $filterSettings) && trim($filterSettings['excludeFilters'])) {
            $this->excludeFilters = t3lib_div::trimExplode(',', $filterSettings['excludeFilters']);
        }

	}



	/**
	 * Adds 'facet.' to configuration parameters for filter facet.
	 *
	 * As we cannot have a '.' in a typoscript key, we add it here for
	 * each facet parameter that is given in filter facetQueryParameter settings.
	 *
	 * @param $facetQueryParameters
	 * @return array
	 */
	protected function addFacetPrefixToTsKeys($facetQueryParameters) {
		$tsArrayWithFacetPrefix = array();
		foreach ($facetQueryParameters as $key => $value) {
			$tsArrayWithFacetPrefix['facet.' . $key] = $value;
		}
		return $tsArrayWithFacetPrefix;
	}



	/**
	 * Returns associative array of exclude filters for given TS configuration
	 *
	 * TODO put all this stuff into abstract data provider class!
	 *
	 * @throws Exception if configuration for exclude filters is incorrect
	 * @return array Array with exclude filters. Encoded as (array('filterboxIdentifier' => array('excludeFilter1','excludeFilter2',...)))
	 */
	protected function buildExcludeFiltersArray() {

		$excludeFiltersAssocArray = array($this->filterConfig->getFilterboxIdentifier() => array($this->filterConfig->getFilterIdentifier()));

		if($this->excludeFilters) {
			foreach($this->excludeFilters as $excludeFilter) {

				list($filterboxIdentifier, $filterIdentifier) = explode('.', $excludeFilter);

				if ($filterIdentifier != '' && $filterboxIdentifier != '') {
				    $excludeFiltersAssocArray[$filterboxIdentifier][] = $filterIdentifier;
				} else {
					throw new Exception('Wrong configuration of exclude filters for filter '. $this->filterConfig->getFilterboxIdentifier() . '.' . $this->filterConfig->getFilterIdentifier() . '. Should be comma seperated list of "filterboxIdentifier.filterIdentifier" but was ' . $excludeFilter . ' 1281102702');
				}

			}
		}

		return $excludeFiltersAssocArray;

	}

}