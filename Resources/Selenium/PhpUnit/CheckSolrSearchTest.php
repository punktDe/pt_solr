<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 punkt.de GmbH
 *  Authors:
 *    Christian Herberger <herberger@punkt.de>,
 *    Ursula Klinger <klinger@punkt.de>,
 *    Daniel Lienert <lienert@punkt.de>,
 *    Joachim Mathes <mathes@punkt.de>
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

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

/**
 * @package westfalen001
 * @subpackage Selenium\PhpUnit\Login
 */
class CheckSolrSearch extends PHPUnit_Extensions_SeleniumTestCase {
	/**
	 * @return void
	 */
	protected function setUp() {
		$this->setBrowserUrl($GLOBALS['SeleniumUrl']);
	}

	protected function tearDown() {
	}


	/**
	 * @test
	 */
	public function checkSearchFieldOnMainPage() {
		// go to page
		$this->open($GLOBALS['SeleniumUrl']);
		$this->waitForPageToLoad("30000");

		// type in Solr Searchfield
		$this->type('//div[@id="search"]/form/div/input','Lebensmittelgase');
		$this->clickAndWait('name=tx_solr[submit_button]');

		// Check Resultsite
		$this->assertEquals('Suche', $this->getText('css=h1'));
		// Check Results
		$this->assertTrue($this->isElementPresent('class=tx-solr-result-content'));
	}
}

?>