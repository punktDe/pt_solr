####################################################
# List configuration for default solr usage
# with pt_extlist
#
# @author Michael Knoll <knoll@punkt.de>
# @package pt_solr
####################################################

plugin.tx_ptsolr.mvc.callDefaultActionIfActionCantBeResolved = 1



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



		# Uncomment for debugging information to be displayed
		# debug = 1



    	# TODO remove this, once DI issues in pt_extlist have been resolved!
    	# Data source is injected via standard DI in solr backend, but we still
    	# have assertions in pt_extlist to check, whether class is set in configuration
    	#
    	# ATTENTION This setting WON'T have any effect on the actual data source class
    	# used for this backend!
    	dataSourceClass = Tx_PtSolr_Extlist_DataBackend_DataSource_SolrDataSource



        searchWordFilter = searchWordFilterbox.searchWordFilter



        doSearchOnEmptySearchWord = 1



        wildcardSearchIsAllowed = 0



        dataMapperClass = Tx_PtSolr_Extlist_DataBackend_DataMapper_SolrDataMapper



        queryInterpreterClass = Tx_PtSolr_Extlist_DataBackend_QueryInterpreter_SolrInterpreter



        # Make sure to have Tx_PtSolr_Extlist_DataBackend_QueryModifier_HighlightingModifier set as query modifier
        # in your query modifier chain to make this working!
        highlighting {

            # If set to 1, highlighting will be enabled
            enable = 1

            # Settings here correnspond 1:1 to the possible settings in http://wiki.apache.org/solr/HighlightingParameters
            hl {
                # Restrict highlighting to certain fields or use '*' to highlight them all
                fl = content
                # Highlighting results in solr are given in its own list. Here you can adjust the length of this list (how many results for highlighting should be returned?)
                snippets = 1
                # Here you can set the length of a highlighting snippet that is returned in response->highlighting
                fragsize = 100
            }

            # Set tags to be used for highlighting
            simple_pre = <strong>
            simple_post = </strong>

            # If set to true, hl.q parameter will be overwritten with original searchword.
            # This can be useful, if we manipulate q in such a way, that it is no longer useful
            # for highlighting.
            useOriginalSearchWordForHighlighting = 1
        }



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
        #sorting = created desc



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

            50 {
                queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_HighlightingModifier
            }

            60 {
            	queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryFieldsModifier
            }

        }



        facetQueryModifierChain {

            20 {
            	queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_QueryFieldsModifier
            }

            30 {
                queryModifierClass = Tx_PtSolr_Extlist_DataBackend_QueryModifier_UserAccessGroupModifier
            }

        }



        # If set to 1, searchword is respected when querying solr for facets
        respectSearchwordOnFacetQuery = 1



        # If set to 1, an empty searchword leads to an non-fullfillable solr query in backend
        emptyFacetsOnEmptySearch = 1



        # Set up query fields and boosting
        qf = content^40.0, title^5.0, keywords^2.0, tagsH1^5.0, tagsH2H3^3.0, tagsH4H5H6^2.0, tagsInline^1.0

    }


    ######################################################
    # Fields configuration for solr
    #
    # Field names must correspond to field names
    # set in solr schema file.
    ######################################################

    fields {

        # Document Id
        id {
            table = __none__
            field = id
        }

        # Id of the page
        uid {
            table = __none__
            field = uid
        }

        pid {
            table = __none__
            field = pid
        }

        created {
            table = __none__
            field = created
        }

        changed {
            table = __none__
            field = changed
        }

        language {
            table = __none__
            field = language
        }

        access {
            table = __none__
            field = access
        }

        title {
            table = __none__
            field = title
        }

        author {
            table = __none__
            field = author
        }

        description {
            table = __none__
            field = description
        }

        abstract {
            table = __none__
            field = abstract
        }

        site {
            table = __none__
            field = site
        }

        content {
            table = __none__
            field = content
        }

        score {
            table = __none__
            field = score
        }

        rootline {
            table = __none__
            field = rootline
        }

        type {
            table = __none__
            field = type
        }

        url {
            table = __none__
            field = url
        }

        abstract {
            table = __none__
            field = abstract
        }

        teaser {
            table = __none__
            field = teaser
        }

        fileExtension {
            table = __none__
            field = fileExtension
        }

        starttime {
            table = __none__
            field = starttime
        }

        endtime {
            table = __none__
            field = endtime
        }

    }


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