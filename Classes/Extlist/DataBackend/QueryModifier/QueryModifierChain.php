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
 * Class implements query modifier chain.
 *
 * Query modifier chain modifies solr query by different modifiers, each registered in the chain.
 *
 * TODO this class should extend object collection
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
class Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChain implements Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface {

    /**
     * Holds an array of TS query chain configuration settings
     * 
     * @var array
     */
    protected $configurationSettings;

    /**
     * Holds instance of solr data backend
     *
     * @var Tx_PtSolr_Extlist_DataBackend_SolrDataBackend
     */
    protected $dataBackend;



    /**
     * Holds an array of query modifiers to be used to modifiy queries
     *
     * @var array<Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface>
     */
    protected $queryModifiers = array();



    /**
     * Constructor for query modifier
     * @param array $queryModifierChainConfigurationSettings
     */
    public function __construct(array $queryModifierChainConfigurationSettings) {
        $this->configurationSettings = $queryModifierChainConfigurationSettings;
    }



    /**
     * Injector for solr data backend
     *
     * @param Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $solrDataBackend
     * @return void
     */
    public function _injectDataBackend(Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $solrDataBackend) {
        $this->dataBackend = $solrDataBackend;
    }



    /**
     * Modifies given query due to functionality of modifiers in current modifier chain
     *
     * @param tx_solr_Query $solrQuery
     * @return tx_solr_Query
     */
    public function modifyQuery(tx_solr_Query $solrQuery) {
        foreach ($this->queryModifiers as $queryModifier) { /* @var $queryModifier Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface */
            $queryModifier->modifyQuery($solrQuery);
        }
        return $solrQuery;
    }



    /**
     * Adds given modifier to chain of modifiers
     *
     * @param Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface $queryModifier
     * @return void
     */
    public function addModifier(Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface $queryModifier) {
        $this->queryModifiers[] = $queryModifier;
    }



    /**
     * Getter for query modifiers registered at this modifier chain
     *
     * @return array<Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface>
     */
    public function getQueryModifiers() {
        return $this->queryModifiers;
    }
    
}
?>