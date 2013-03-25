<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Ines Heberle <heberle@punkt.de>, punkt.de GmbH
 *
 *
 *  All rights reserved
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
 * Class implements a scheduler task for running a Seleniumtest and a ping, which
 *
 * - Check the availability of Server
 * - Check functionality of Solar
 *
 * @author Ines Heberle <heberle@punkt.de>
 * @package Tx_PtBrukeraxsSolr
 * @subpackage Scheduler
 */
include t3lib_extMgm::extPath('solr') . 'report/class.tx_solr_report_solrstatus.php';

class Tx_PtSolr_Tasks_SeleniumAndPingTest extends tx_scheduler_Task{


	/**
	 * Connection Manager
	 *
	 * @var tx_solr_ConnectionManager
	 */
	protected $connectionManager = NULL;

	/**
	 * This is the main method that is called when a task is executed
	 * It MUST be implemented by all classes inheriting from this one
	 * Note that there is no error handling, errors and failures are expected
	 * to be handled and logged by the client implementations.
	 * Should return true on successful execution, false on error.
	 *
	 * @return boolean    Returns true on successful execution, false on error
	 */
	public function execute(){
		if ($this->pingSolrServer() && $this->runCommand() === TRUE){
			return TRUE;
		}
		 return FALSE;
	}

	/**
	 * Compiles a collection of status checks against each configured Solr server.
	 *
	 * @see typo3/sysext/reports/interfaces/tx_reports_StatusProvider::getStatus()
	 */
	public function pingSolrServer() {
		$ping = new tx_solr_report_SolrStatus($this);
		$report = $ping->getStatus();
		$errormessage = implode(' ', $report);
		$status = substr($errormessage[1],-1,3) . substr($errormessage[2],-1,3);
		$dump = var_export($report, true);

		if ($status === 'OK'){
			return TRUE;
		} else {
			$to = $this->email;
			$subject = 'Scheduler Task for Ping';
			$message = $errormessage . $dump;
			mail($to, $subject, $message, $additional_headers = null, $additional_parameter = null);
			return FALSE;
		}
	}

	/*
	 * run Command in Shell
	 * Command calls Selenium Tests for Solr
	 * @return bool
	 */
	public function runCommand() {
		$path = t3lib_extMgm::extPath('pt_solr') . 'Resources/Selenium/Configuration/selenium-solr.xml';
		//
		$command = 'phpunit -c ' . $path;
		exec($command, $output);

		$errormessage = implode(' ', $output);

		if (substr($output[8],0,2) === 'OK'){
			return TRUE;
		} else {
			$to = $this->email;
			$subject = 'Scheduler Task for Selenium';
			$message = $errormessage . ' FAIL';
			mail($to, $subject, $message, $additional_headers = null, $additional_parameter = null);
			return FALSE;
		}
	}

	public function getAdditionalInformation() {
		return 'Error E-Mail Adress: ' . $this->email;
	}
}
