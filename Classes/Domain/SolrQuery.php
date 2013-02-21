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
 * This class extends tx_solr's query class to add
 * our own functionality.
 *
 * @author Michael Knoll
 * @package Domain
 */
class Tx_PtSolr_Domain_SolrQuery extends tx_solr_Query {

    /**
     * Constructor for query
     *
     * @param $keywords
     */
    public function __construct($keywords) {
        parent::__construct($keywords);
    }

    

    /**
     * Set parameter on current query.
     *
     * Given query parameters will be encoded in query string like ...&$key=$value&...
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function setQueryParameter($key, $value) {
        $this->queryParameters[$key] = $value;
    }



    /**
     * Set multiple parameters on current query.
     *
     * Given query parameters array( 'key1' => 'value1', ...) will be encoded in query string like
     * ...&$key1=$value1&...
     *
     * @param $parametersArray
     * @return void
     */
    public function setQueryParametersByParametersArray($parametersArray) {
        foreach($parametersArray as $key => $value) {
            $this->setQueryParameter($key, $value);
        }
    }

}
