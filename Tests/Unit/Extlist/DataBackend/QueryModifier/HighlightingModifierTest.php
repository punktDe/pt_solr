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
 * Class implements testcase for highlighting modifier
 *
 * @package Tests
 * @subpackage Unit\Extlist\DataBackend\QueryModifier
 * @author Michael Knoll
 */
class Tx_PtSolr_Tests_Unit_Extlist_DataBackend_QueryModifier_HighlightingModifierTest extends Tx_PtSolr_Tests_BaseTestcase {

	private $tsFakeSettings = array(
		'highlighting' => array(
			'enable' => '1',
			'hl' => array (
				'fl' => '*',
				'snippets' => '1',
				'fragsize' => '100'
			),
			'simple_pre' => '<strong>',
			'simple_post' => '</strong>',
			'useOriginalSearchWordForHighlighting' => '1'
		)
	);



	/**
	 * Holds faked searchword as return value for data backend
	 *
	 * @var string
	 */
	private $fakeSearchWords = 'testword';



	/** @test */
	public function modifierSetsParametersOnSolrQueryAsExpected() {
		$dataBackendMock = $this->getDataBackendMockWithTsFakeSettings();

		$setParams = array();
		$solrQueryMock = $this->getMock('tx_solr_Query', array('addQueryParameter'), array(), '', FALSE);
		$solrQueryMock
				->expects($this->any())
				->method('addQueryParameter')
				->will($this->returnCallback(
					function($key, $value) use (&$setParams) {
						$setParams[$key] = $value;
					}
				)
		);

		$highlightModifier = new Tx_PtSolr_Extlist_DataBackend_QueryModifier_HighlightingModifier();
		$highlightModifier->injectDataBackend($dataBackendMock);
		$highlightModifier->modifyQuery($solrQueryMock);

		$this->assertTrue($setParams['hl'] == 'true');
		$this->assertTrue($setParams['hl.fl'] == '*');
		$this->assertTrue($setParams['hl.snippets'] == '1');
		$this->assertTrue($setParams['hl.fragsize'] == '100');
		$this->assertTrue($setParams['hl.simple.pre'] == '<strong>');
		$this->assertTrue($setParams['hl.simple.post'] == '</strong>');
		$this->assertTrue($setParams['hl.q'] == $this->fakeSearchWords);
	}



	/**
	 * Returns mocked data backend returning fake ts settings
	 *
	 * @return Tx_PtSolr_Extlist_DataBackend_SolrDataBackend
	 */
	private function getDataBackendMockWithTsFakeSettings() {
		$dataBackendMock = $this->getMock('Tx_PtSolr_Extlist_DataBackend_SolrDataBackend', array('getDataBackendSettings','getSearchWords', 'highlightingIsEnabled'), array(), '', FALSE);
		$dataBackendMock->expects($this->any())->method('getDataBackendSettings')->will($this->returnValue($this->tsFakeSettings));
		$dataBackendMock->expects($this->once())->method('getSearchWords')->will($this->returnValue($this->fakeSearchWords));
		$dataBackendMock->expects($this->any())->method('highlightingIsEnabled')->will($this->returnValue(TRUE));
		return $dataBackendMock;
	}

}
?>
