<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * Interpreter for SOLR queries
 * 
 * @package Domain
 * @subpackage SolrDataBackend\SolrInterpreter
 * @author Daniel Lienert
 * @author Michael Knoll
 */
class Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter {

    /**
     * Singleton instance of this class
	 *
     * @var Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter
     */
    private static $instance = null;



    /**
     * Holds objects for translating different types of criterias
     *
     * @var array<criteriaClassName => Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_TranslatorInterface>
     */
    protected $translatorObjects = array();



    /**
     * Factory method returns singleton instance of this class
	 *
     * @static
     * @return Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter();
            self::initializeTranslators();
        }
        return self::$instance;
    }



    /**
     * Initializes interpreter object by setting translator classes for criterias
	 *
     * @static
     * @return void
     */
    protected static function initializeTranslators() {
        self::$instance->setTranslatorForCriteriaClass('Tx_PtExtlist_Domain_QueryObject_SimpleCriteria', new Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SimpleCriteriaTranslator());

		$andCriteriaTranslator = new Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_AndCriteriaTranslator();
		$andCriteriaTranslator->injectSolrInterpreter(self::$instance);
		self::$instance->setTranslatorForCriteriaClass('Tx_PtExtlist_Domain_QueryObject_AndCriteria',    $andCriteriaTranslator);

		$orCriteriaTranslator = new Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_OrCriteriaTranslator();
		$orCriteriaTranslator->injectSolrInterpreter(self::$instance);
		self::$instance->setTranslatorForCriteriaClass('Tx_PtExtlist_Domain_QueryObject_OrCriteria',    $orCriteriaTranslator);

		self::$instance->setTranslatorForCriteriaClass('Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_RawSolrCriteria', new Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_RawSolrCriteriaTranslator());
    }



    /**
     * Setter for translator objects that should handle translation of given criteria classes
     * 
     * @param string $criteriaClassName Class name of criteria that should be translated by given translator object
     * @param Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_TranslatorInterface $translatorObject Translator for given criteria class
     * @return void
     */
    public function setTranslatorForCriteriaClass($criteriaClassName, Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_TranslatorInterface $translatorObject) {
        $this->translatorObjects[$criteriaClassName] = $translatorObject;
    }



    /**
     * Translates a given criteria
     *
     * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria Criteria to be translated
     * @return string Translated criteria
     */
    public function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
        $criteriaClassName = get_class($criteria);
        $translatorObject = $this->getTranslatorObjectByCriteriaClassName($criteriaClassName);
        return $translatorObject->translateCriteria($criteria);
    }



    /**
     * Returns translator object for given criteria
     *
     * @throws Exception if no translator object is registered for given criteria class name
     * @param string $criteriaClassName
     * @return Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_TranslatorInterface Translator object registered for given criteria class name
     */
    protected function getTranslatorObjectByCriteriaClassName($criteriaClassName) {
        if (!array_key_exists($criteriaClassName, $this->translatorObjects)) {
            throw new Exception('No translator is registered for criteria class ' . $criteriaClassName . ' 1320489975');
        }
        return $this->translatorObjects[$criteriaClassName];
    }



    /**
     * Returns translated criteria(s)
     *
     * @param array<Tx_PtExtlist_Domain_QueryObject_Criteria> Criterias to be translated
     * @return string Translated criterias
     */
    public function translateCriterias(array $criterias) { /* @var $criteria Tx_PtExtlist_Domain_QueryObject_Criteria */
		$translatedCriteriasArray = array();
        foreach($criterias as $criteria) {
            $translatedCriteriasArray[] = $this->translateCriteria($criteria);
        }
        $translatedCriterias = implode(' AND ', $translatedCriteriasArray);
        return $translatedCriterias;
    }

}
?>