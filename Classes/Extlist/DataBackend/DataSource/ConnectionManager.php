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
 * Class implements connection manager for solr connections.
 *
 * This class acts as an adapter for solr connection manager.
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Extlist\DataBackend\DataSource
 */
class Tx_PtSolr_Extlist_DataBackend_DataSource_ConnectionManager {

    /**
     * Holds solr connection manager to get current connection from
     *
     * @var tx_solr_ConnectionManager
     */
    protected $solrConnectionManager;



    /**
     * Constructor for connection manager
     *
     * @param tx_solr_ConnectionManager $solrConnectionManager
     */
    public function __construct(tx_solr_ConnectionManager $solrConnectionManager) {
        $this->solrConnectionManager = $solrConnectionManager;
    }



    /**
     * Creates solr connection for given pageId and sysLanguageUid.
     *
     * Note: There must be a solr configuration on the root page for given page.
     *
     * @param $pageId
     * @param $sysLanguageUid
     * @return tx_solr_SolrService
     */
    public function getConnection($pageId, $sysLanguageUid) {
        return $this->solrConnectionManager->getConnectionByPageId($pageId, $sysLanguageUid);
    }

}
?>