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
 * Class extends ExtListContext for usage in pt_solr
 *
 * @package ExtlistContext
 * @author Michael Knoll
 */
class Tx_PtSolr_Extlist_SolrExtlistContext extends Tx_PtExtlist_ExtlistContext_ExtlistContext {

	/**
	 * Holds filter registered to be search word filter
	 *
	 * @var Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	protected $searchWordFilter;



	/**
	 * Holds instance of solr data backend
	 *
	 * @var Tx_PtExtlistSolr_Domain_SolrDataBackend_DataBackend
	 */
	protected $dataBackend;



	/**
	 * Returns filter registered to be search word filter
	 *
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	public function getSearchWordFilter() {
		return $this->dataBackend->getSearchwordFilter();
	}



	/**
	 * Returns full qualified filter identifier for search word filter
	 *
	 * @return string
	 */
	public function getSearchWordFilterIdentifier() {
		return $this->listSettings['backendConfig']['searchWordFilter'];
	}

}
?>