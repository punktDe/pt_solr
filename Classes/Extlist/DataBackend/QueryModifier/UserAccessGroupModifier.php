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
 * Class implements modifier that sets current usergroups to restrict results of query to current fe_user
 *
 * @author Michael Knoll
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
class Tx_PtSolr_Extlist_DataBackend_QueryModifier_UserAccessGroupModifier extends Tx_PtSolr_Extlist_DataBackend_QueryModifier_AbstractQueryModifier {

    /**
     * Modifies given query due to functionality of current modifier
     *
     * This modifier sets user groups in given solr query. Each solr
     * document has an access rootline and hence can be access-restricted
     * via fe_groups.
     *
     * @param tx_solr_Query $solrQuery
     * @return void
     */
    public function modifyQuery(tx_solr_Query $solrQuery) {
        $solrQuery->setUserAccessGroups($this->getLoggedInUserGroupUids());
    }



    /**
     * Returns UID of user groups of currently logged in user
     * 
     * @return array
     */
    protected function getLoggedInUserGroupUids() {
        return explode(',', $GLOBALS['TSFE']->gr_list);
    }
    
}
?>