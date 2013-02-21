<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Michael Knoll <knoll@punkt.de>
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
 * Class implements a testcase for the pt_extlist data provider for solr facet data.
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package Tests
 * @subpackage Unit\Extlist\DataBackend
 * @see Tx_PtSolr_Extlist_DataBackend_DataProvider_FacetDataProvider
 */
class Tx_PtSolr_Tests_Unit_Extlist_DataBackend_DataProvider_FacetDataProviderTest extends Tx_PtExtbase_Tests_Unit_AbstractBaseTestcase {

	/** @test */
	public function classExists() {
		$this->assertTrue(class_exists('Tx_PtSolr_Extlist_DataBackend_DataProvider_FacetDataProvider'));
	}

}
