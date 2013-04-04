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
 * Class implements modifier that sets query fields (qf) parameter in solr query
 *
 * @author Michael Knoll
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
class Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryFieldsModifier extends Tx_PtSolr_Extlist_DataBackend_QueryModifier_AbstractQueryModifier {

    /**
     * Modifies given query due to functionality of current modifier
     *
     * This modifier sets qf parameter from backend configuration for solr extlist configuration
     *
     * @param tx_solr_Query $solrQuery
     * @return void
     */
    public function modifyQuery(tx_solr_Query $solrQuery) {
		$queryFields = $this->dataBackend->getConfigurationBuilder()->buildDataBackendConfiguration()->getSettings('qf');
		if ($queryFields !== array()) {
			$solrQuery->setQueryFieldsFromString($queryFields);
		}
    }

}