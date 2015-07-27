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
		$this -> handle = fopen($app_path.date('d-M-Y',strtotime('now')).'.csv', 'a+');

		$this -> makeIdsCars = array(140,203,375,800,900,1100,121,1750,1700,1900,2000,1950,3100,3500,3850,4025,4350,4400,4700,112,5300,83,5600,5700,5900,6200,6325,6600,6800,7000,7400,7700,8600,8800,172,9000,205,204,9900,122,186,10850,11000,11050,11600,11650,11900,12100,12400,12600,13200,13450,13900,14400,14600,14700,14800,14845,15200,15400,15500,15900,16200,16600,16700,16800,137,17200,17300,30011,17500,17700,17900,18700,18875,18975,19000,149,19300,19600,19800,20000,20100,20200,20700,21600,21700,125,21800,22000,22500,22900,23000,188,100,23100,23500,23600,23800,23825,189,135,24100,24200,24400,24500,25200,25100,25300,113,25650,1400);
		$this -> makesCars	= array('Abarth','AC','Acura','Aixam','Alfa Romeo','ALPINA','Artega','Asia Motors','Aston Martin','Audi','Austin','Austin Healey','Bentley','BMW','Borgward','Brilliance','Bugatti','Buick','Cadillac','Casalini','Caterham','Chatenet','Chevrolet','Chrysler','Citroën','Cobra','Corvette','Dacia','Daewoo','Daihatsu','DeTomaso','Dodge','Ferrari','Fiat','Fisker','Ford','GAC Gonow','Gemballa','GMC','Grecav','Hamann','Holden','Honda','Lobster','Hyundai','Infiniti','Isuzu','Iveco','Jaguar','Jeep','Kia','Königsegg','KTM','Lada','Lamborghini','Lancia','Land Rover','Landwind','Lexus','Ligier','Lincoln','Lotus','Mahindra','Maserati','Maybach','Mazda','McLaren','Mercedes Benz','MG','Microcar','MINI','Mitsubishi','Morgan','Nissan','NSU','Oldsmobile','Opel','Pagani','Peugeot','Piaggio','Plymouth','Pontiac','Porsche','Proton','Renault','Rolls Royce','Rover','Reputation','Saab','Santana','Seat','Skoda','Smart','speedART','Spyker','Ssangyong','Subaru','Suzuki','Talbot','Tata','TECHART','Tesla','Toyota','Satellite','Triumph','TVR','Volkswagen','Volvo','Wartburg','Westfield','Wiesmann','Other');

		$this -> makeIdsBike = array(220,387,500,800,224,1500,1650,98,2625,30032,105,2900,3200,184,3400,12,3500,3750,3800,142,73,4200,30031,4800,30033,30035,163,6350,6700,7200,7550,7600,7800,69,183,8433,8442,9500,9675,10,9700,70,9975,10300,10600,30037,11000,190,11100,11200,11400,103,12000,12450,174,13100,13125,13250,74,94,13650,228,13900,171,14200,212,15000,15350,15700,192,193,16300,16400,230,16900,126,108,18100,18125,118,18300,18400,72,18875,191,19190,19300,19400,19600,19975,20250,229,179,20790,20795,30036,21750,21900,22550,162,173,22800,22912,23016,23600,23675,106,11,225,24050,30034,24400,24595,25000,99,25212,107,26000,151,26450,26500,1400);
		$this -> makesBike	= array('Beliebig','Access Motor','Adly','Aeon','Aixam','American Ironhorse','Aprilia','Arctic Cat','Baotian','Barossa','Bashan','Beeline','Benelli','Beta','Big Dog Motorcycles','Bimota','Blata','BMW','Bombardier','Boom','BRP','BSA','Buell','Burelli','Cagiva','Can Am','Cectek','CFMOTO','CPI','Daelim','Derbi','Dinli','DKW','Ducati','e-max','emco','E-Ton','Explorer','Gasgas','Generic','GG Motorradtechnik','Gilera','GOES','Gorilla','Harley-Davidson','Hercules','Herkules','Honda','Horex','Husaberg','Husqvarna','Hyosung','Indian','Italjet','Jawa','Jinling','Kawasaki','Keeway','Kimi','Kingway','Knievel','Kreidler','KSR','KTM','Kumpan','Kymco','Lambretta','Laverda','Lifan','Linhai','LML','Luxxon','Maico','Malaguti','Mash','MBK','Megelli','Motobi','Moto Guzzi','Moto Morini','Motowell','MV Agusta','Mz','Norton','NSU','Online','Pegasus','Peugeot','PGO','Piaggio','Polaris','Puch','Quadix','Quadro','Rewaco','Rieju','Rivero','Royal Enfield','Sachs','Seikel','Sherco','Shineray','Simson','Skyteam','SMC','Suzuki','SYM','Tauris','TGB','Thunderbike','TM','Triton','Triumph','Ural','Vespa','VICTORY','Voxan','WMI','Yamaha','Zero','Zhongyu','Zündapp','Andere');

		$this -> makeIdsCarvan = array(156,400,700,194,1000,1600,2400,18,2700,2800,206,3000,164,3300,16,4500,4300,4812,4825,4900,5000,5400,30038,5500,5700,5800,5900,208,6100,6300,6400,6500,7100,7125,7300,210,136,7900,8000,8100,8429,8300,8400,8420,119,8500,8700,8800,8825,8900,9000,9100,214,14,9200,9288,9600,143,9800,185,10000,10400,10500,10800,10900,19,11027,11300,231,207,30039,12100,12750,12870,13000,13275,13300,14500,14300,15800,16000,16500,16800,16950,17200,17450,17600,17700,17800,17823,17850,138,18600,18650,95,18800,19000,19050,19100,221,19179,19300,19500,19700,19900,20283,20300,20400,20500,20700,20800,20900,21000,21100,21200,21300,2300,21925,17,22600,13,22925,209,23350,23555,79,152,23700,23900,24000,71,24300,21500,213,24700,15,25200,25400,25500,25600,25700,30040,25800,25900,120,1400);
		$this -> makesCarvan	= array('ABI','Adria','Maple','Airstream','Alpha','Arca','Autostar','Avento','Bavaria-Camp','Bawemo','Bela','Benimar','Beyerland','Bimobil','Bravia','Burow','Bürstner','Carado','Caravelair','Caro','Carthago','Challenger','Chateau','Chausson','Chrysler','CI International','Citroën','Clever','Coachmen','Concorde','Cristall','CS motorhomes','Dehler','Delta','Dethleffs','DOMO','Dopfer','Due Erre','Eifelland','Elnagh','Esterel','Eura Mobil','Euro Liner','EVM','eXtreme','Fendt','FFB - Tabbert','Fiat','Fisherman','Fleetwood','Ford','Ford / Reimo','Forster','Four-Wheel Camper','Frankia','FR-Mobil','General Motors','Giottiline','Globecar','Fortunately Mobile','Granduca','Hehn','Heku','Hobby','Holiday Rambler','Home-Car','HRZ','HYMER / ERIBA','HymerCar','Ilusion','ITINEO','Iveco','Joint','Kabe','Karmann','Kip','Knaus','Laika','La Strada','LMC','M + M Mobile','MAN','Mazda','McLouis','Mercedes Benz','Miller','Mirage','Mitsubishi','Mobilvetta','Monaco','Moncayo','Morelo','Niesmann + Bischoff','Niewiadów','NobelART','North Star','Opel','Orange Camp','Ormocar','PLA','Paul & Paula','Peugeot','Phoenix','Pilote','Pössl','Raclet','Rapido','Reimo','Reisemobile Beier','Renault','Rimor','Riva','Riviera','RMB','Roadtrek','Robel-Mobil','Roller Team','SEA','Seitz','DIY','Six-Pac','Sloop','Sprite','Sterckeman','Sunlight','Sun Living','T @ b','Tabbert','TEC','Phonetic','Trigano','Triple E','TSL Landsberg / Rockwood','VANTourer','Vario','Vögele','Volkswagen','Vineyard','Weippert','Westfalia','Wilk','Wingamm','Winnebago','Woelcke','XGO','Other');
//
		switch (intval(1)) {
			case 1:
					foreach ($this -> makesCars as $keymakes => $valuemakes) {
						$searchId = $this -> makeIdsCars[$keymakes];
						for ($i=1; $i < 51 ; $i++) {
							$this -> writeLog("Extracting contacts for $valuemakes ...<br/>");
							$url = 'http://suchen.mobile.de/auto/search.html?scopeId=C&isSearchRequest=true&pageNumber='.$i.'&makeModelVariant1.makeId='.$searchId;
							$this -> writeLog("$url");
							$html = file_get_html($url,'suchen.mobile.de');
							$tablerows = $html -> find('div.result-items',0) ->find('.result-item');
							$itemLinks = array();
							if(empty($tablerows))
								break;
							foreach ($tablerows as $key => $value) {
								if(!empty($value -> find('.vehicleDetails a',0) -> href))
									$itemLinks[] = $value -> find('.vehicleDetails a',0) -> href;
							}
							foreach ($itemLinks as $key => $value) {
								$html = file_get_html($value,'suchen.mobile.de');
								$vcard = $html -> find ('section.vcard',0) -> find('p.phone');
								$phoneNumbers = array();
								foreach ($vcard as $key => $value) {
									$phoneNumbers = $this -> extractPhone($value -> plaintext);
									if(!empty($phoneNumbers)){
										$this -> writeLog($phoneNumbers,"<br/>");
										if(!empty($phoneNumbers)){
											fputcsv($this -> handle, array($phoneNumbers));
											mysql_query('INSERT IGNORE INTO phoneDatabase(phone,adddate) VALUES("'.$phoneNumbers.'","'.date('d-M-Y',strtotime('now')).'")');
										}										
									}
								}
							}


							unset($itemLinks);
						}
						// echo "Script Paused for 5 seconds to avoid abuse...";
						sleep(5);
					}
					foreach ($this -> makesBike as $keymakes => $valuemakes) {
						$searchId = $this -> makeIdsBike[$keymakes];
						for ($i=1; $i < 51 ; $i++) { 
							$this -> writeLog("Extracting contacts for $valuemakes ...<br/>");
							$url = 'http://suchen.mobile.de/auto/search.html?scopeId=C&isSearchRequest=true&pageNumber='.$i.'&makeModelVariant1.makeId='.$searchId;
							$this -> writeLog("$url");
							$html = file_get_html('http://suchen.mobile.de/auto/search.html?scopeId=C&isSearchRequest=true&pageNumber='.$i.'&makeModelVariant1.makeId='.$searchId,'suchen.mobile.de');
							$tablerows = $html -> find('div.result-items',0) ->find('.result-item');
							$itemLinks = array();
							if(empty($tablerows))
								break;
							foreach ($tablerows as $key => $value) {
								if(!empty($value -> find('.vehicleDetails a',0) -> href))
									$itemLinks[] = $value -> find('.vehicleDetails a',0) -> href;
							}
							foreach ($itemLinks as $key => $value) {
								$html = file_get_html($value,'suchen.mobile.de');
								$vcard = $html -> find ('section.vcard',0) -> find('p.phone');
								$phoneNumbers = array();
								foreach ($vcard as $key => $value) {
									$phoneNumbers = $this -> extractPhone($value -> plaintext);
									if(!empty($phoneNumbers)){
										
										$this -> writeLog($phoneNumbers."<br/>");
										
										if(!empty($phoneNumbers)){
											fputcsv($this -> handle, array($phoneNumbers));
											mysql_query('INSERT IGNORE INTO phoneDatabase(phone,adddate) VALUES("'.$phoneNumbers.'","'.date('d-M-Y',strtotime('now')).'")');
										}										
									}
								}
							}


							unset($itemLinks);
						}
						// echo "Script Paused for 5 seconds to avoid abuse...";
						sleep(5);
					}
					foreach ($this -> makesCarvan as $keymakes => $valuemakes) {
						$searchId = $this -> makeIdsCarvan[$keymakes];
						for ($i=1; $i < 51 ; $i++) { 
							$this -> writeLog("Extracting contacts for $valuemakes ...<br/>");
							$url = 'http://suchen.mobile.de/auto/search.html?scopeId=C&isSearchRequest=true&pageNumber='.$i.'&makeModelVariant1.makeId='.$searchId;
							$this -> writeLog("$url");
							$html = file_get_html('http://suchen.mobile.de/auto/search.html?scopeId=C&isSearchRequest=true&pageNumber='.$i.'&makeModelVariant1.makeId='.$searchId,'suchen.mobile.de');
							$tablerows = $html -> find('div.result-items',0) ->find('.result-item');
							$itemLinks = array();
							if(empty($tablerows))
								break;
							foreach ($tablerows as $key => $value) {
								if(!empty($value -> find('.vehicleDetails a',0) -> href))
									$itemLinks[] = $value -> find('.vehicleDetails a',0) -> href;
							}
							foreach ($itemLinks as $key => $value) {
								$html = file_get_html($value,'suchen.mobile.de');
								$vcard = $html -> find ('section.vcard',0) -> find('p.phone');
								$phoneNumbers = array();
								foreach ($vcard as $key => $value) {
									$phoneNumbers = $this -> extractPhone($value -> plaintext);
									if(!empty($phoneNumbers)){
										
										$this -> writeLog($phoneNumbers."<br/>");
										
										if(!empty($phoneNumbers)){
											fputcsv($this -> handle, array($phoneNumbers));
											mysql_query('INSERT IGNORE INTO phoneDatabase(phone,adddate) VALUES("'.$phoneNumbers.'","'.date('d-M-Y',strtotime('now')).'")');
										}										
									}
								}
								
							}


							unset($itemLinks);
						}
						// echo "Script Paused for 5 seconds to avoid abuse...";
						sleep(5);
					}
				break;
		}
		
	}

	private function extractPhone($text){
		$text = ltrim($text, 'Tel. :');
		$text = ltrim($text, ' +49 (0)');
		$text = str_replace('(0)',"",$text);
		$text = str_replace('&nbsp;',"",$text);
		$phone = preg_replace('/[^A-Za-z0-9]/', '', $text);
		$this -> writeLog("<br/> Formating and validating $phone");
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
	// private function extractPhone($text){
	// 	$this -> writeLog("Formating and validating $text");
	// 	$str = str_replace("Tel", "", $text);
	// 	$str = str_replace(".", "", $str);
	// 	$str = str_replace(":", "", $str);
	// 	$str = str_replace("(", "", $str);
	// 	$str = str_replace(")", "", $str);
	// 	$str = str_replace("+49", "", $str);
	// 	$str = str_replace("&nbsp;", "", $str);
	// 	$str = (string)trim($str);
	// 	$this -> writeLog(strlen($str)." ".substr($str,0,3) ."== '015' ".substr($str,0,3) ."== '016' ". substr($str,0,3) ." == '017' ".substr($str,0,2) ." == '15' ". substr($str,0,2) ."== '16' ". substr($str,0,2) ." == '17'");
	// 	if((strlen($str) == 10 || strlen($str) == 11) && (substr($str,0,3) == '015' || substr($str,0,3) == '016' || substr($str,0,3) == '017' || substr($str,0,2) == 15 || substr($str,0,2) == 16 || substr($str,0,2) == 17)){
	// 		return $str;
	// 	}
	// 	else{
	// 		return '';
	// 	}
	// }
	private function getEmail($str)
	{
		preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $str,$s);
		return $s[0];

	}

	private function unichr($u) {
		$responseData;
	     foreach ($u as $key => $value) {
	     	$responseData[] = mb_convert_encoding('&#' . intval($value) . ';', 'UTF-8', 'HTML-ENTITIES');
	     }
	     return implode("", $responseData);
	}
	private function char_at($str,$pos)
	{
	  return $str{$pos};
	}

}

$tmp = new ScraperX();

