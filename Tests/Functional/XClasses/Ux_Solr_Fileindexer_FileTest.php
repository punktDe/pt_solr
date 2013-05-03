<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
*
*
*  All rights reserved
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
 * Testcase for x-class implementation of the fileindexer default class of the solr extension
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package XClasses
 * @see ux_tx_solr_fileindexer_File
 */
class ux_tx_solr_fileindexer_FileTest extends Tx_PtSolr_Tests_BaseTestcase {

	/** @test */
	public function instanziationOfFileIndexerClassReturnsExpectedXClass() {
		$fileIndexer = t3lib_div::makeInstance('tx_solr_fileindexer_File');
		$this->assertTrue(is_a($fileIndexer, 'ux_tx_solr_fileindexer_File'));
	}



	/** @test */
	public function fileIndexerUsesDefaultExtractionMethodIfNotSetToUseTikaInServerMode() {
		ux_tx_solr_fileindexer_File::useTikaServerInMode(FALSE); // We have to set this explicitly, since it is set to TRUE per default in pt_solr's localconf!
		$fileIndexerMock = $this->getMock('ux_tx_solr_fileindexer_File', array('getContentByDefaultExtraction'), array(), '', FALSE);
		$fileIndexerMock->expects($this->once())->method('getContentByDefaultExtraction'); /* @var $fileIndexerMock ux_tx_solr_fileindexer_File */
		$fileIndexerMock->getContent();
	}



	/** @test */
	public function fileIndexerUsesTikaInServerModeForTextExtractionIfSetToDoSo() {
		ux_tx_solr_fileindexer_File::useTikaServerInMode(TRUE);
		$fileIndexerMock = $this->getMock('ux_tx_solr_fileindexer_File', array('getContentFromTikaServer'), array(), '', FALSE);
		$fileIndexerMock->expects($this->once())->method('getContentFromTikaServer'); /* @var $fileIndexerMock ux_tx_solr_fileindexer_File */
		$fileIndexerMock->getContent();
	}



	/** @test */
	public function ifAvailableTikaServerIsUsedForTextExtraction() {
		if (!$this->tikaServerIsRunning()) {
			$this->markTestSkipped('Tika server is not running so this test is skipped');
			return;
		}

		ux_tx_solr_fileindexer_File::useTikaServerInMode(TRUE);
		$fileIndexer = t3lib_div::makeInstance('tx_solr_fileindexer_File', 'typo3conf/ext/pt_solr/Tests/Functional/Fixtures/TextExtractionSample.txt'); /* @var $fileIndexer tx_solr_fileindexer_File */
		$extractedContent = $fileIndexer->getContent();
		$this->assertSame('Hallo Welt!', $extractedContent);
	}



	private function tikaServerIsRunning() {
		// TODO do we always want to have TIKA running on 12345?!?
		$tikaRunning = @fsockopen("127.0.0.1", 12345, $errno, $errstr, 10);
		return $tikaRunning;
	}

}