<?php

$ptSolrBasePath = t3lib_extMgm::extPath('pt_solr');
$classesBasePath = $ptSolrBasePath . 'Classes/';
$testsBasePath = $ptSolrBasePath . 'Tests/';

$classesToBeAutoloaded = array(
	'Tx_PtSolr_Tests_BaseTestcase' => $testsBasePath . 'BaseTestcase.php',
	'Tx_PtSolr_Controller_AbstractActionController' => $classesBasePath . 'Controller/AbstractActionController.php'
);


// Autoloader only works with lowercased class names which makes Copy&Paste a mess here
$lowerCasedClassesToBeAutoloaded = array();

foreach ($classesToBeAutoloaded as $classNameUpperCase => $path) {
	$lowerCasedClassesToBeAutoloaded[strtolower($classNameUpperCase)] = $path;
}

return $lowerCasedClassesToBeAutoloaded;

?>