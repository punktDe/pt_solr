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
 * Class implements testcase for searchResultInformation
 *
 * @package Tests
 * @subpackage Unit/Domain
 * @author Michael Knoll
 */
class Tx_PtSolr_Tests_Unit_Domain_SearchResultInformationTest extends Tx_PtSolr_Tests_BaseTestcase {

	/** @test */
	public function getSearchPhraseReturnsCurrentSearchPhrase() {
		$dataBackendMock = $this->getMock(
			'Tx_PtSolr_Extlist_DataBackend_SolrDataBackend',
			array('getSearchWords'),
			array(),
			'',
			FALSE
		);
		$dataBackendMock
			->expects($this->once())
			->method('getSearchWords')
			->will($this->returnValue('blablabla'));
		$searchResultInformation = new Tx_PtSolr_Domain_SearchResultInformation($dataBackendMock);
		$this->assertEquals('blablabla', $searchResultInformation->getSearchPhrase());
	}



	/** @test */
	public function getResultsCountReturnsCurrentResultCount() {
		$dataBackendMock = $this->getMock(
			'Tx_PtSolr_Extlist_DataBackend_SolrDataBackend',
			array('getTotalItemsCount'),
			array(),
			'',
			FALSE
		);
		$dataBackendMock
			->expects($this->once())
			->method('getTotalItemsCount')
			->will($this->returnValue(1337));
		$searchResultInformation = new Tx_PtSolr_Domain_SearchResultInformation($dataBackendMock);
		$this->assertEquals(1337, $searchResultInformation->getResultsCount());
	}

}
?>



