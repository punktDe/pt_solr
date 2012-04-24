####################################################
# List configuration for default solr usage
# with pt_extlist
#
# @author Michael Knoll <knoll@punkt.de>
# @package Typo3
# @subpackage pt_solr
####################################################



######################################################
## Setup for default pt_solr list configuration "solr"
######################################################
plugin.tx_ptextlist.settings.listConfig.solr {

    ######################################################
    # Define partials for solr document types
    # Each key is a document type we get from solr
    # __default__ sets a default partial that is used
    #   if no configuration for key could be found
    ######################################################
    resultPartials {
        __default__ = EXT:pt_solr/Resources/Private/Partials/Results/default.html
        pages = EXT:pt_solr/Resources/Private/Partials/Results/page.html
        tt_news = EXT:pt_solr/Resources/Private/Partials/Results/tt_news.html
    }



    ######################################################
    # Backend configuration for solr
    ######################################################
    #backendConfig < plugin.tx_ptextlist.prototype.backend.solr
    backendConfig {

    	dataBackendClass = Tx_PtSolr_Extlist_DataBackend_SolrDataBackend



        searchWordFilter = searchWordFilterbox.searchWordFilter



        doSearchOnEmptySearchWord = 0



        dataMapperClass = Tx_PtSolr_Extlist_DataBackend_DataMapper_SolrDataMapper



        queryInterpreterClass = Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter



        ######################################################
        # Sorting for reslut list
        #
        # A sort ordering must include a field name
        # (or the pseudo-field score),
        # followed by a space,
        # followed by a sort direction (asc or desc).
        #
        # Multiple sort orderings can be separated by a comma,
        # ie: <field name> <direction>[,<field name> <direction>]...
        ######################################################
        sorting = created desc



        queryModifierChain {

            10 {
                queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_SearchWordModifier
            }

            20 {
                queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_FilterModifier
            }

            30 {
            	queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_LimitModifier
            }

            40 {
                queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_UserAccessGroupModifier
            }

        }



        facetQueryModifierChain {

            30 {
                queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_UserAccessGroupModifier
            }

        }



        # If set to 1, searchword is respected when querying solr for facets
        respectSearchwordOnFacetQuery = 1



        # If set to 1, an empty searchword leads to an non-fullfillable solr query in backend
        emptyFacetsOnEmptySearch = 1

    }


    ######################################################
    # Fields configuration for solr
    #
    # Field names must correspond to field names
    # set in solr schema file.
    ######################################################
    fields < plugin.tx_ptextlist.prototype.fields.solr



    ######################################################
    # Columns configuration for solr result list
    ######################################################
    columns {

        1 {
            fieldIdentifier = type
            columnIdentifier = type
        }

        2 {
            fieldIdentifier = id
            columnIdentifier = id
        }

        10 {
            fieldIdentifier = title
            columnIdentifier = title
        }

        20 {
            fieldIdentifier = url
            columnIdentifier = url
        }

        30 {
            fieldIdentifier = content
            columnIdentifier = content

            renderObj = TEXT
            renderObj {
                field = content
                cropHTML = 500 | ...
            }
        }

        100 {
            fieldIdentifier = score
            columnIdentifier = score
            label = Score
        }

        101 {
            fieldIdentifier = score
            columnIdentifier = relevance

            renderObj = TEXT
            renderObj {
                current = 1
                setCurrent.field = score
                setCurrent.wrap = | * 100
                prioriCalc = intval
            }
        }

        102 {
            fieldIdentifier = score
            columnIdentifier = relevanceFill

            renderObj = TEXT
            renderObj {
                current = 1
                setCurrent.field = score
                setCurrent.wrap = 100 - | * 100
                prioriCalc = intval
            }
        }

        200 {
            fieldIdentifier = rootline
            columnIdentifier = rootline
        }

        400 {
            fieldIdentifier = teaser
            columnIdentifier = teaser
        }

        500 {
            fieldIdentifier = abstract
            columnIdentifier = abstract
        }

        730 {
            fieldIdentifier = fileExtension
            columnIdentifier = fileExtension
        }

        740 {
            fieldIdentifier = starttime
            columnIdentifier = starttime
        }

        750 {
            fieldIdentifier = endtime
            columnIdentifier = endtime
        }

    }



    #####################################################################
    # Filter / Faceting configuration
    #####################################################################
    filters {

        #########################
        ## Searchword Filterbox
        #########################
        searchWordFilterbox < plugin.tx_ptextlist.prototype.filterBox
        searchWordFilterbox {
            filterConfigs {

                #########################
                ## Searchword Filter
                #########################
                20 < plugin.tx_ptextlist.prototype.filter.string
                20 {
                    filterIdentifier = searchWordFilter
                    fieldIdentifier = content
                }

            }

        }

    }



    #####################################################################
    # Pager configuration
    #####################################################################
    pager < plugin.tx_ptextlist.prototype.pager
    pager {
        itemsPerPage = 10

        pagerConfigs {
            delta {
                delta = 3
            }

            default < plugin.tx_ptextlist.settings.listConfig.solr.pager.pagerConfigs.delta
        }

    }

}