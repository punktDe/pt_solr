<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
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
 * x-class implementation of the fileindexer default class of the solr extension
 *
 * This class can use tika in a server mode for text extraction. For starting TIKA in server mode use the following command:
 * $ java -jar <path_to_typo3>/typo3conf/ext/pt_solr/Resources/Private/Tika/tika-app-1.3.jar -t --server --port 12345 > /dev/null &
 *
 * If you want to start TIKA in a seperate processs that is permanently running on the server, use the following command:
 * $
 *
 * Use the following command to get the contents of a file using the TIKA server:
 * $ nc 127.0.0.1 12345 < path_to_file
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package XClasses
 * @see ux_tx_solr_fileindexer_FileTest
 */
class ux_tx_solr_fileindexer_File extends tx_solr_fileindexer_File {

	/**
	 * If set to TRUE, tika will be used in server mode.
	 *
	 * @var bool
	 */
	private static $useTikaInServerMode = FALSE;



	/**
	 * Set this to true if you want to use tika in server mode.
	 *
	 * @param bool $useTikaInServerMode
	 */
	public static function useTikaServerInMode($useTikaInServerMode = TRUE) {
		self::$useTikaInServerMode = $useTikaInServerMode;
	}



	/**
	 * Gets a file's textual content or if it is not a text file it's textual
	 * representation.
	 *
	 * @return	string	The file's string content.
	 */
	public function getContent() {
		$content = NULL;
		if (self::$useTikaInServerMode) {
			$content = $this->getContentFromTikaServer();
		} else {
			$content = $this->getContentByDefaultExtraction();
		}
		return $content;
	}



	/**
	 * Uses the Tika server mode to extract content from associated file
	 *
	 * @return string
	 */
	protected function getContentFromTikaServer() {
		if (empty($this->content)) {
			$fileContent = '';
			$mimeType = $this->getMimeType(); // not time relevant!!!

			if ($mimeType == 'text/plain') {
				// we can read text files directly
				$fileContent = file_get_contents($this->absolutePath); // not time relevant!!!
			} elseif ($this->canExtractText()) { // not time relevant!!!
				// other subtypes should be handled by the text service
				// TODO use sockets to do this job!
				$command = 'nc 127.0.0.1 12345 < ' . escapeshellarg($this->absolutePath);
				exec($command, $fileContent);
				$fileContent = implode(' ', $fileContent);
			} else {
				// return an empty string
				$fileContent = '';
			}
			$this->content = $this->cleanContent($fileContent);
		}

		return $this->content;
	}



	/**
	 * Uses the text-extraction method implemented in the parent class to the content of the associated file
	 *
	 * @return string
	 */
	protected function getContentByDefaultExtraction() {
		return parent::getContent();
	}



	/**
	 * see tx_solr_HtmlContentExtractor::cleanContent($fileContent); for original method!
	 */
	protected function cleanContent($content) {
		$content = preg_replace('@[\x00-\x08\x0B\x0C\x0E-\x1F]@', ' ', $content);

		// Remove '<' and '>'
		$content = str_replace(array('<', '>'), '', $content);

		$content = str_replace(array("\t", "\n", "\r", '&nbsp;'), ' ', $content);
		$content = trim($content);

		return $content;
	}

}