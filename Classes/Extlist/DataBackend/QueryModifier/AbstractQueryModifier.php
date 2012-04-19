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
 * Class implements abstract query modifier to be extended by query modifiers for solr data backend
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
abstract class Tx_PtSolr_Extlist_DataBackend_QueryModifier_AbstractQueryModifier implements Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface {

    /**
     * Holds instance of associated solr data backend
     *
     * @var Tx_PtSolr_Extlist_DataBackend_SolrDataBackend
     */
    protected $dataBackend;



    /**
     * Injector for solr data backend
     *
     * @param Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $solrDataBackend
     * @return void
     */
    public function injectDataBackend(Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $solrDataBackend) {
        $this->dataBackend = $solrDataBackend;
    }

}
?>