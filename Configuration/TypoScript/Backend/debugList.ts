####################################################
# List configuration for backend debug list for
# pt_solr.
#
# @author Michael Knoll <knoll@punkt.de>
# @package pt_solr
####################################################

module.tx_ptsolr.settings.listConfigs.backendDebug {

    backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
    backendConfig {

		tables (
			tx_solr_indexqueue_item item
		)

		baseFromClause (

		)

		baseWhereClause (

		)

		baseGroupByClause(

		)


    }



    fields {

        uid {
            table = item
            field = uid
        }

        root {
            table = item
            field = root
        }

        item_type {
            table = item
            field = item_type
        }

        item_uid {
            table = item
            field = item_uid
        }

        indexing_configuration {
            table = item
            field = indexing_configuration
        }

    }



    columns {

        1 {
            fieldIdentifier = uid
            columnIdentifier = uid
            label = UID
        }

        2 {
            fieldIdentifier = root
            columnIdentifier = root
            label = Root
        }

        10 {
            fieldIdentifier = item_type
            columnIdentifier = item_type
            label = Item Type
        }

        20 {
            fieldIdentifier = item_uid
            columnIdentifier = item_uid
            label = Item UID
        }

        30 {
            fieldIdentifier = indexing_configuration
            columnIdentifier = indexing_configuration
            label = Indexing Configuration
        }

    }



    filters {

        debugFilterbox < plugin.tx_ptextlist.prototype.filterBox
        debugFilterbox {
            filterConfigs {

                20 < plugin.tx_ptextlist.prototype.filter.string
                20 {
                    filterIdentifier = itemUidFilter
                    fieldIdentifier = uid
                }

            }

        }

    }



    #####################################################################
    # Pager configuration
    #####################################################################
    pager < plugin.tx_ptextlist.prototype.pager
    pager {
        itemsPerPage = 100

        pagerConfigs {
            delta {
                delta = 3
            }

            default < plugin.tx_ptextlist.settings.listConfig.solr.pager.pagerConfigs.delta
        }

    }

}