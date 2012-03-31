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
 * Class implements testcase for pt_solr AbstractActionController
 *
 * @package Tests
 * @author Michael Knoll
 */
class Tx_PtSolr_Tests_Unit_Controller_AbstractActionControllerTest extends Tx_PtSolr_Tests_BaseTestcase {

	/**
	 * Holds abstract controller implementation for testing
	 *
	 * @var Tx_PtSolr_Tests_Unit_Controller_AbstractActionControllerImplementationMock
	 */
	protected $abstractControllerMock;



	/**
	 * Sets up testcase before tests are run
	 */
	public function setUp() {
		$this->abstractControllerMock = new Tx_PtSolr_Tests_Unit_Controller_AbstractActionControllerImplementationMock();
	}



	/** @test */
	public function testSetUp() {
		$this->assertTrue(class_exists('Tx_PtSolr_Tests_Unit_Controller_AbstractActionControllerImplementationMock'), 'Failed assertion that class Tx_PtSolr_Tests_Unit_Controller_AbstractActionControllerImplementationMock exists!');
	}



	/** @test */
	public function determineListIdentifierDeterminesIdentifierSetInSettings() {
		// We first test for default list identifier
		$this->abstractControllerMock->__test_determineSolrExtlistIdentifier();
		$this->assertEquals(Tx_PtSolr_Controller_AbstractActionController::SOLR_LIST_IDENTIFIER, $this->abstractControllerMock->__test_getSolrExtlistIdentifier());

		// We then check for list identifier from given settings
		$this->abstractControllerMock->__test_setSettings(array('listIdentifier' => 'testListIdentifier'));
		$this->abstractControllerMock->__test_determineSolrExtlistIdentifier();
		$this->assertEquals($this->abstractControllerMock->__test_getSolrExtlistIdentifier(), 'testListIdentifier');
	}



	/** @test */
	public function initExtlistContextCallsExtlistContextFactoryCorrectly() {
		$solrExtlistContextMock = $this->getMock('Tx_PtSolr_Extlist_SolrExtlistContext');
		$solrExtlistContextFactoryMock = $this->getMock(
			'Tx_PtSolr_Extlist_SolrExtlistContextFactory',
			array('getContextByListIdentifierNonStatic'),
			array(),
			'',
			FALSE
		);
		$solrExtlistContextFactoryMock
				->expects($this->once())
				->method('getContextByListIdentifierNonStatic')
				->with($this->abstractControllerMock->__test_getSolrExtlistIdentifier())
				->will($this->returnValue($solrExtlistContextMock));
		$this->abstractControllerMock->injectSolrExtlistContextFactory($solrExtlistContextFactoryMock);
		$this->abstractControllerMock->__test_initExtlistContext();
	}



	/** @test */
	public function initializeDefaultComponentsInViewInitializesDefaultComponents() {
		$searchWordFilterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterInterface');
		$renderedListDataMock = $this->getMock('Tx_PtExtlist_Domain_Model_List_ListData');
		$pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerInterface');
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array(), array(), '', FALSE);
		$dataBackendMock = $this->getMock('Tx_PtExtlistSolr_Domain_SolrDataBackend_DataBackend', array(), array(), '', FALSE);
		$solrExtlistContextMock = $this->getMock(
			'Tx_PtSolr_Extlist_SolrExtlistContext',
			array('getSearchwordFilter', 'getRenderedListData', 'getPager', 'getPagerCollection', 'getDataBackend'),
			array(),
			'',
			FALSE
		);
		$solrExtlistContextMock->expects($this->once())->method('getSearchwordFilter')->will($this->returnValue($searchWordFilterMock));
		$solrExtlistContextMock->expects($this->once())->method('getRenderedListData')->will($this->returnValue($renderedListDataMock));
		$solrExtlistContextMock->expects($this->once())->method('getPager')->will($this->returnValue($pagerMock));
		$solrExtlistContextMock->expects($this->once())->method('getPagerCollection')->will($this->returnValue($pagerCollectionMock));
		$solrExtlistContextMock->expects($this->once())->method('getDataBackend')->will($this->returnValue($dataBackendMock));

		$viewMock = $this->getMock(
			'Tx_PtExtbase_View_BaseView',
			array('assign'),
			array(),
			'',
			FALSE
		);
		$viewMock->expects($this->at(0))->method('assign')->with('searchWordFilter', $searchWordFilterMock);
		$viewMock->expects($this->at(1))->method('assign')->with('resultList', $renderedListDataMock);
		$viewMock->expects($this->at(2))->method('assign')->with('pager', $pagerMock);
		$viewMock->expects($this->at(3))->method('assign')->with('pagerCollection', $pagerCollectionMock);
		$viewMock->expects($this->at(4))->method('assign')->with('searchResultInformation', $this->anything());

		$this->abstractControllerMock->__test_setView($viewMock);
		$this->abstractControllerMock->__test_setSolrExtlistContext($solrExtlistContextMock);

		$this->abstractControllerMock->__test_initializeDefaultComponentsInView();
	}

}



/**
 * Implementation dummy for abstract action controller which we test here
 *
 *
 * As we test an abstract class here, it makes sense to have an actual implementation for
 * testing hence otherwise we would have to do a lot of mocking and tricking...
 */
class Tx_PtSolr_Tests_Unit_Controller_AbstractActionControllerImplementationMock extends Tx_PtSolr_Controller_AbstractActionController {

	public function __test_setView($view) {
		$this->view = $view;
	}



	public function __test_setSolrExtlistContext($solrExtlistContext) {
		$this->solrExtlistContext = $solrExtlistContext;
	}



	public function __test_initializeDefaultComponentsInView() {
		$this->initializeDefaultComponentsInView();
	}



	public function __test_setSettings($settings) {
		$this->settings = $settings;
	}



	public function __test_determineSolrExtlistIdentifier() {
		$this->determineSolrListIdentifier();
	}



	public function __test_getSolrExtlistIdentifier() {
		return $this->solrExtlistIdentifier;
	}



	public function __test_initExtlistContext() {
		$this->initExtlistContext();
	}

}
?>



