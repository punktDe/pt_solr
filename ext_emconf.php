<?php

########################################################################
# Extension Manager/Repository config file for ext: "pt_solr"
#
# Auto generated by Extbase Kickstarter 2012-02-10
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'pt_solr',
	'description' => 'Solr search engine frontend based on Extbase and Fluid.',
	'category' => 'plugin',
	'author' => 'Michael Knoll',
	'author_email' => 'knoll@punkt.de',
	'author_company' => 'punkt.de',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid,solr,pt_extlist,solrfile',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.0-dev',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
			'solr' => '3.0.0',
			'solrfile' => '1.0.1'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>