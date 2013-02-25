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
 * Class implements testcase for SolrExtlistContext
 *
 * @package Tests
 * @author Michael Knoll
 */
class Tx_PtSolr_Tests_Unit_Extlist_SolrExtlistContextTest extends Tx_PtSolr_Tests_BaseTestcase {

	/**
	 * Holds instance of Tx_PtSolr_Extlist_SolrExtlistContext to be tested
	 *
	 * @var Tx_PtSolr_Extlist_SolrExtlistContext
	 */
	protected $solrExtlistContext;



	/**
	 * Set up testcase
	 */
	public function setup() {
		$this->solrExtlistContext = new Tx_PtSolr_Extlist_SolrExtlistContext();
	}



	/** @test */
	public function getSearchWordFilterIdentifierReturnsCorrectIdentifier() {
		$searchWordFilterIdentifier = 'searchWordFilterIdentifier';
		$dataBackendMock = $this->getMock(
			'Tx_PtSolr_Extlist_DataBackend_SolrDataBackend',
			array('getSearchwordFilterIdentifier'),
			array(),
			'',
			FALSE
		);
		$dataBackendMock->expects($this->once())->method('getSearchwordFilterIdentifier')->will($this->returnValue($searchWordFilterIdentifier));
		$this->solrExtlistContext->_injectDataBackend($dataBackendMock);
		$this->assertEquals($this->solrExtlistContext->getSearchWordFilterIdentifier(), $searchWordFilterIdentifier);
	}



	/** @test */
	public function getSearchWordFilterReturnsSearchWordFilter() {
		$dataBackendMock = $this->getMock(
			'Tx_PtSolr_Extlist_DataBackend_SolrDataBackend',
			array('getSearchwordFilter'),
			array(),
			'',
			FALSE
		);
		$filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterInterface');
		$dataBackendMock->expects($this->once())->method('getSearchwordFilter')->will($this->returnValue($filterMock));
		$this->solrExtlistContext->_injectDataBackend($dataBackendMock);
		$this->assertEquals($this->solrExtlistContext->getSearchWordFilter(), $filterMock);
	}

}
?>



