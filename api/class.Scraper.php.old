<?php

include('config.php');
/*Mobile.de SCraper Class*/
/**
 argument 1 is to set the no of results per page b/w ranging 1-8
 argument 2 is to set the no pages to be scraped
 argument 3 is the Excel sheet Name to be read for building the query 
 Please ensure there are not spaces in the file name(Excel File)
*/
include ('scraper.lib.php');

class ScraperX{

	private $category;
	private $resultSet;
	private $city;
	private $handle;


	public function __construct(){
		global $app_path;
		$handle = fopen($app_path.'process.txt', 'a+');
					fputcsv($handle, array(getmypid()));
		fclose($handle);

		$this -> handle = fopen($app_path.date('d-M-Y',strtotime('now')).'.csv', 'a+');
		$this -> configCategories = array();

		fputcsv($this -> handle, array('Phone'));
		$handle = fopen('category_config.csv', 'r+');
		while ($row = fgetcsv($handle)) {
			$this -> configCategories[] = $row;
		}
		fclose($handle);

		foreach ($this -> configCategories as $key => $value) {
			$this -> TotalPages = 5;
			$this -> writeLog("<br/>| Scraping Category - ".$value[1]);
			$this -> writeLog("<br/>| Category URL => ".$value[0]);
			$html = file_get_html($value[0],'kleinanzeigen.ebay.de');
			if(is_object($html) == 1){

				$pages = $html -> find('.pagination-link');
				$pages = $html -> find('.pagination-link',(count($pages) - 1)) -> plaintext;
				$this -> TotalPages = intval($pages);
				
				$this -> writeLog("<br/>| Total Pages to scrape in this category | ".$this -> TotalPages);
				$this -> writeLog("<br/>| Extracting ads link | ");
				$adslinks = $html -> find('.position-relative li');
				foreach ($adslinks as $k => $v) {
					$tmplink = $v -> find('.ad-listitem-main a',0) -> href;
					if($tmplink)
					{
						$tmplink = "http://kleinanzeigen.ebay.de".$tmplink;
						$newHTML = file_get_html($tmplink,'kleinanzeigen.ebay.de');
						$phone = $newHTML -> find('.phoneline-number',0) -> plaintext;

						if(strpos($phone,"...") !== false){
							$this -> writeLog("<br/>| <strong>Your session is expired<br/>1) Please click on stop.<br/>2) Please click on Login to start a new session<br/>3) Start the script.</strong> |");
							exit();
						}

						$phone = $this -> extractPhone($phone);
						$this -> writeLog("<br/>| Scraping ads URL => ".$tmplink." | contact no. - ".$phone."|");
						
						if(!empty(trim($phone))) {
								$this -> writeLog("<br/>|final Phone $phone |");
								fputcsv($this -> handle, array($phone));
								mysql_query('INSERT IGNORE INTO phoneDatabase(phone,adddate) VALUES("'.$phone.'","'.date('d-M-Y',strtotime('now')).'")');
						}
					}

				}


			}

			for ($i=2; $i < $this -> TotalPages; $i++) {
				$tlink = explode("/", $value[0]);
				$count = count($tlink) - 1;
				$hlink = str_replace("/".$tlink[$count], "", $value[0]);
				$dUrl = $hlink.'/seite:'.$i."/".$tlink[$count];
				$this -> writeLog("<br/>| Scraping Category - ".$value[1]);
				$this -> writeLog("<br/>| Category URL => ".$value[0]);
				$nshtml = file_get_html($dUrl,'kleinanzeigen.ebay.de');
				if(is_object($nshtml) == 1){
					$pages = $nshtml -> find('.pagination-link');
					$pages = $nshtml -> find('.pagination-link',(count($pages) - 1)) -> plaintext;
					$this -> TotalPages = intval($pages);
					$this -> writeLog("<br/>| Total Pages to scrape in this category | ".$this -> TotalPages);
					$this -> writeLog("<br/>| Extracting ads link | ");
					$adslinks = $nshtml -> find('.position-relative li');
					foreach ($adslinks as $k => $v) {
						$tmplink = $v -> find('.ad-listitem-main a',0) -> href;
						if($tmplink)
						{
							$tmplink = "http://kleinanzeigen.ebay.de".$tmplink;
							$newHTML = file_get_html($tmplink,'kleinanzeigen.ebay.de');
							$phone = $newHTML -> find('.phoneline-number',0) -> plaintext;
							$this -> writeLog("<br/>| Scraping ads URL => ".$tmplink." | contact no. - ".$phone."|");
							if(strpos($phone,"...") !== false){
								$this -> writeLog("<br/>| <strong>Your session is expired please click on Login to login again and start the script.</strong> |");
								exit();
							}
							
							$phone = $this -> extractPhone($phone);
							$this -> writeLog("<br/>| Scraping ads URL => ".$tmplink." | contact no. - ".$phone."|");


							if(!empty(trim($phone))) {
								$this -> writeLog("<br/>|final Phone $phone |");
								fputcsv($this -> handle, array($phone));
								mysql_query('INSERT IGNORE INTO phoneDatabase(phone,adddate) VALUES("'.$phone.'","'.date('d-M-Y',strtotime('now')).'")');
							}
						}

					}


				}
				if($i % 10 == 0){
					$this -> writeLog("<br/>| Pausing script for 10 seconds to avoid abuse... |");
					sleep(10);
				}


			}

		}
		
	}

	private function cleanString($input){
		return preg_replace("/[^a-zA-Z\ ]+/", "", $input);
	}

	private function writeLog($text){
		global $log_path;
		$handle = fopen($log_path.'scrape.log', 'a+');
		fputcsv($handle, array($text));
		fclose($handle);
	}

	private function parseJson($json){

		foreach ($json['responseData'] -> results as $key => $value) {
			$value -> message = $json['responseData'] -> cursor -> currentPageIndex;
			$this -> resultSet[] = $value;
		}
	}
	private function extractPhone($text){
		$text = ltrim($text, '0');

		$this -> writeLog("<br/> Formating and validating $text");
		$phone = preg_replace('/[^A-Za-z0-9]/', '', $text);
		$length = strlen($phone);
		$cases = ((substr($phone,0,2) === '15') or (substr($phone,0,2) === '16') or (substr($phone,0,2) === '17')) ? 1 : 0;
		$this -> writeLog("<br/> Numbers starting from 15 16 17 ? $cases");
		$lengthcase = (($length == 10) or ($length == 11)) ? 1: 0;
		if($lengthcase === 1 and $cases === 1) {
			$this -> writeLog( "<br/> Number is valid $phone");
			return $phone;
		}
		return NULL;

	}

}

$tmp = new ScraperX();

