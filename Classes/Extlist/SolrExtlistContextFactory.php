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
class Tx_PtSolr_Extlist_SolrExtlistContextFactory extends Tx_PtExtlist_ExtlistContext_ExtlistContextFactory {

	/**
	 * Initialize and return a DataBackend with the given listIndentifier
	 *
	 * @param string $listIdentifier
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public static function getContextByListIdentifier($listIdentifier) {

		if(!array_key_exists($listIdentifier, self::$instances)) {

			$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::getInstanceByListIdentifier($listIdentifier, false);

			if($extListBackend === NULL) {
				$extListTs = self::getExtListTyposcriptSettings($listIdentifier);
				self::loadLifeCycleManager();

				Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($extListTs);
				$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($listIdentifier);

				$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($configurationBuilder);
			}

			self::$instances[$listIdentifier] = self::buildContext($extListBackend);

		}

		return self::$instances[$listIdentifier];
	}



	/**
	 * Non-static wrapper for getContextByListIdentifier
	 *
	 * @param $listIdentifier
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public function getContextByListIdentifierNonStatic($listIdentifier) {
		return self::getContextByListIdentifier($listIdentifier);
	}



	/**
	 * Build the extlistContext
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @return Tx_PtExtlist_ExtlistContext_SolrExtlistContext $extlistContext
	 */
	protected static function buildContext(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$extlistContext = new Tx_PtSolr_Extlist_SolrExtlistContext();

		$extlistContext->injectDataBackend($dataBackend);
		$extlistContext->init();

		return $extlistContext;
	}

}
?>