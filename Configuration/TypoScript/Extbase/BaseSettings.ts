####################################################
# Extbase base configuration for pt_solr
#
# @author Michael Knoll <knoll@punkt.de>
# @package Typo3
# @subpackage pt_solr
####################################################


plugin.tx_ptsolr {

	view {
		templateRootPath = {$plugin.tx_ptsolr.view.templateRootPath}
		partialRootPath = {$plugin.tx_ptsolr.view.partialRootPath}
		layoutRootPath = {$plugin.tx_ptsolr.view.layoutRootPath}
	}

	persistence {
		storagePid = {$plugin.tx_ptsolr.persistence.storagePid}
	}

}