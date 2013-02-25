<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2012 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
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
 * Class implements testcase for Solr Data Backend
 *
 * @package Tests
 * @subpackage Unit\Extlist\DataBackend
 * @author Joachim Mathes
 */
class Tx_PtSolr_Tests_Unit_Extlist_DataBackend_SolrDataBackendTest extends Tx_PtSolr_Tests_BaseTestcase {

	/**
	 * @test
	 */
	public function methodGetIterationListDataThrowsException() {
		try {
			$solrDataBackendMock = $this->getMockBuilder('Tx_PtSolr_Extlist_DataBackend_SolrDataBackend') /** @var Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $solrDataBackendMock */
					->setMethods(array('dummy'))
					->disableOriginalConstructor()
					->getMock();
			$solrDataBackendMock->getIterationListData();
		} catch (Exception $e) {
			return;
		}
		$this->fail('No exception has been thrown when trying to get iteration list data from solr data backend.');
	}

}
