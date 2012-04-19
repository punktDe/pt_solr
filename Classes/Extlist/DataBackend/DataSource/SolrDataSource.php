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
 * Class implements data source for solr indexes
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert
 * @package Domain
 * @subpackage SolrDataBackend\SolrDataSource
 */
class Tx_PtSolr_Extlist_DataBackend_DataSource_SolrDataSource {

    /**
     * @var tx_solr_Search
     */
    protected $search;



    /**
     * @var tx_solr_SolrService
     */
    protected $connection;



	/**
	 * Constructor for solr data source
	 *
	 * @param Tx_PtSolr_Extlist_DataBackend_DataSource_ConnectionManager $connectionManager
	 */
	public function __construct(Tx_PtSolr_Extlist_DataBackend_DataSource_ConnectionManager $solrConnectionManager) {
		$this->connection = $solrConnectionManager->getConnection(
			$GLOBALS['TSFE']->id,
			$GLOBALS['TSFE']->sys_language_uid
		);
		$this->search = t3lib_div::makeInstance('tx_solr_Search', $this->connection);
	}
    


	/**
	 * Performs solr search
	 *
     * @param tx_solr_Query $query Solr query to be performed
     * @param int $offset Pager offset to start resultset at
     * @param int $limit Pager limit to set number of results per page
	 * @return Apache_Solr_Response
	 */
	public function search(tx_solr_Query $query, $offset = 0, $limit = 10) {
		$response = $this->search->search($query, $offset, $limit);
        return $response;
	}



    /**
     * @param tx_solr_SolrService $connection
     * @return void
     */
    public function injectSolrConnection(tx_solr_SolrService $connection) {
        $this->connection = $connection;
    }



	/**
	 * Returns the solr search object
	 *
	 * @return tx_solr_Search
	 */
	public function getSearchObject() {
		return $this->search;
	}

}
?>