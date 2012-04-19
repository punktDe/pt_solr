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
 * Class implements factory to build query modifier chain
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Extlist
 * @subpackage DataBackend\QueryModifier
 */
class Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChainFactory implements t3lib_Singleton {

    /**
     * Returns instance of query modifier chain
     * 
     * @throws Exception
     * @param Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $dataBackend
     * @param array $queryModifierChainSettings
     * @return Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChain
     */
    public function getInstance(Tx_PtSolr_Extlist_DataBackend_SolrDataBackend $dataBackend, array $queryModifierChainSettings) {
        $queryModifierChain = new Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierChain($queryModifierChainSettings);
        $queryModifierChain->injectDataBackend($dataBackend);

        foreach ($queryModifierChainSettings as $order => $queryModifierConfiguration) {
            $queryModifierClassName = $queryModifierConfiguration['queryModifierClass'];
            if (!class_exists($queryModifierClassName)) {
                throw new Exception('Query modifier class "' . $queryModifierClassName . '" set in TS settings does not exist! 1320388590');
            }
            $queryModifier = new $queryModifierClassName; /* @var $queryModifier Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface */
            if (!is_a($queryModifier, 'Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface')) {
                throw new Exception('Query modifier class ' . $queryModifierClassName . ' does not implement required interface Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryModifierInterface! 1320388591');
            }
            $queryModifier->injectDataBackend($dataBackend);
            $queryModifierChain->addModifier($queryModifier);
        }
        return $queryModifierChain;
    }

}
?>