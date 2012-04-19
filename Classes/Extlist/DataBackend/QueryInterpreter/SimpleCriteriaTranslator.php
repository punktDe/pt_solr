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
 * Translator for AND criteria
 * 
 * @package Domain
 * @subpackage SolrDataBackend\SolrInterpreter
 * @author Daniel Lienert
 * @author Michael Knoll
 */
class Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SimpleCriteriaTranslator implements Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_TranslatorInterface {

    /**
     * Holds an instance of solr query object for escaping etc.
     *
     * @var tx_solr_Query
     */
    protected $solrQuery = null;



    /**
     * Constructor for translator class
     */
    public function __construct() {
        // TODO think about a way to inject this!
        $this->solrQuery = new tx_solr_Query('');
    }



	/**
	 * translate simple criteria 
	 * 
	 * @param $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 * @return string
	 */
	public function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
        $operator = $criteria->getOperator();
		// TODO at the moment, we get table.field for fields - we don't want to have that here...
		list($table, $field) = explode('.', $criteria->getField());
		$value = $criteria->getValue();

        switch ($operator) {
            case '=' :
                return $this->translateEqualsCriteria($field, $value);
                break;

            case 'IN' :
                return $this->translateInCriteria($field, $value);
                break;

			case '<=' :

			case '<' :
				// TODO we could actually translate 'less' criteria by negating 'biggerThanEqualsCriteria'!
				return $this->translateLessThanEqualsCriteria($field, $value);
				break;

			case '>=' :

			case '>' :
				// TODO we could actually translate 'bigger' criteria by negating 'lessThanEqualsCriteria'!
				return $this->translateGreaterThanEqualsCriteria($field, $value);
				break;

            default:
                throw new Exception('Unknown operator ' . $operator . ' in simple criteria. Cannot translate this in solr data backend! 1320480989');
        }
	}



    /**
     * Translates equals criteria (=) by transformin it to
     *
     * $field:"$value"
     *
     * @static
     * @param $field Field to be compared
     * @param $value Value to be compared
     * @return string Translated equals criteria
     */
    protected function translateEqualsCriteria($field, $value) {
        return $field . ':"' . $this->solrQuery->escape($value) . '"';
    }



	/**
	 * Translates less then equals criteria (<=) by transforming it to
	 *
	 * $field:[* TO $value]
	 *
	 * @param $field
	 * @param $value
	 * @return string
	 */
	protected function translateLessThanEqualsCriteria($field, $value) {
		return $field . ':[* TO ' . $this->solrQuery->escape($value) . ']';
	}



	/**
	 * Translates bigger than equals criteria (>=) by transforming it to
	 *
	 * $field:[$value TO *]
	 *
	 * @param $field
	 * @param $value
	 * @return string
	 */
	protected function translateGreaterThanEqualsCriteria($field, $value) {
		return $field . ':[' . $this->solrQuery->escape($value) . ' TO *]';
	}



    /**
     * Translates IN criteria (IN) by transforming it to
     *
     * ($field:"$value[0]" OR "$value[1]" OR ... )
     *
     * @static
     * @param $field Field that should contain given values
     * @param $value array Values to be compared against field
     * @return string Solr query string for IN query
     */
    protected function translateInCriteria($field, array $value) {
        $escapedValues = array();
        foreach($value as $valueString) {
            $escapedValues[] = '"' . $valueString . '"';
        }
		$escapedValueString = '';
		if (count($escapedValues) > 0) {
			$escapedValueString = '(' . $field . ':' . implode(' OR ' . $field . ':', $escapedValues) . ')';
		}
        return $escapedValueString;
    }

}
?>