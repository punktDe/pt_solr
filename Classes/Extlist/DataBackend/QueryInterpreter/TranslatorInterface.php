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
 * Interface for solr criteria translator
 *
 * @package Domain
 * @subpackage SolrDataBackend\SolrInterpreter
 * @author Michael Knoll
 */
interface Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_TranslatorInterface {

    /**
     * Translates given criteria into solr query string
     *
     * @abstract
     * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria
     * @return string Translated solr query string
     */
    public function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria);

}
?>