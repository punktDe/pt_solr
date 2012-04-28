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
 * Class implements a mapper that maps solr response documents to list data structure 
 *
 * @package Extlist
 * @subpackage DataBackend\DataMapper
 * @author Daniel Lienert
 * @author Michael Knoll
 */
class Tx_PtSolr_Extlist_DataBackend_DataMapper_SolrDataMapper extends Tx_PtExtlist_Domain_DataBackend_Mapper_AbstractMapper {
    
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $fieldConfigurationCollection;


	
	/**
	 * @return void
	 */
	public function init() {
		$this->fieldConfigurationCollection = $this->configurationBuilder->buildFieldsConfiguration();
	}


	
	/**
	 * @param array $responseDocuments
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getMappedListData(array $responseDocuments = array()) {
		// We use solr list data object here
		$listData = new Tx_PtSolr_Extlist_Model_ListData();
		#$listData = new Tx_PtExtlist_Domain_Model_List_ListData();

		foreach ($responseDocuments as $responseDocument) { /* @var $responseDocument Apache_Solr_Document */
			$listData->addRow($this->createRowFromResponseDocument($responseDocument));
		}

		return $listData;
	}



	/**
	 * Creates row in list data structure from given solr response
	 *
	 * @param $responseDocument Apache_Solr_Document
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected function createRowFromResponseDocument(Apache_Solr_Document $responseDocument) {
		$mappedRow = new Tx_PtExtlist_Domain_Model_List_Row();

		foreach ($this->mapperConfiguration as $field) { /** @var $field Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig */
			$fieldData = $responseDocument->getField($field->getField());

			if (is_array($fieldData)) {
				$mappedRow->createAndAddCell($fieldData['value'], $field->getIdentifier());
			} else {
				$mappedRow->createAndAddCell('', $field->getIdentifier());
				// TODO it makes no sense to throw exception here!
				#Throw new Exception('The field ' . $field->getField() . ' was not found in the Solr response document.', 1319791232);
			}
		}

		// TODO think about adding all fields from solr response in resulting row (iterating over fields of response, checking whether cell has already been set in row and set it, if not)

		return $mappedRow;
	}

}
?>