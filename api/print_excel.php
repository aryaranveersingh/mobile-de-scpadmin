<?php

/**
 * @param Array of Array or Array - (Data Dump) to be printed in Excel in sheet
 * @param Data headers (Array) to be used
 * @param Array of Array or Array of Names to be used as Sheet name
 * @param AutoFilter Flag TRUE or FALSE
 * @param fileName - optional parameter default current_timestamp
 */
include 'lib/Classes/PHPExcel.php';
ini_set('memory_limit', '9996M');

class ExcelPrinter {

    private $prn;
    private $excelHeaders;
    private $namePrn;
    private $enable_autoFilter;
    private $xlsName;
    private $xlsobj;
    private $sheetObj;
    public $savedFileName;

    function __construct($arrayOfArray, $headers, $sheetNameArray = NULL, $filter = NULL, $fileName = NULL) {

        if ((isset($arrayOfArray) && is_array($arrayOfArray)) && (is_null($headers) || is_array($headers)) && (is_null($sheetNameArray) || is_array($sheetNameArray))) {

            $this -> prn = $arrayOfArray;
            $this -> excelHeaders = $headers;
            $this -> namePrn = $sheetNameArray;
            $this -> enable_autoFilter = (int)$filter;
            $this -> xlsName = $fileName;
            $this->savedFileName = $this -> initXLSX();

        } else {
            echo json_encode(array('Invalid post Data, please refer the readme file for input clarification'));
        }

    }

    private function initXLSX() {

        require_once 'lib/Classes/PHPExcel/IOFactory.php';

        /*Create a object to create a new excel file*/
        $this -> xlsobj = new PHPExcel();
        return $this -> createSheets();

    }

    private function createSheets() {

        if (!is_null($this -> namePrn)) {

            foreach ($this -> namePrn as $key => $value) {

                $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $value);
                // Attach the New worksheet in the PHPExcel object
                $this -> xlsobj -> addSheet($myWorkSheet, $key);
                $this -> xlsobj -> setActiveSheetIndex($key) -> getTabColor()->setRGB('004D40');
                // Print the array in current sheet;
                $this -> printData($key);
            }

            return $this -> genSheet();
        }

    }

    private function printData($keyInput) {
         $this -> xlsobj -> getActiveSheet() -> getDefaultColumnDimension()->setWidth(25);
        //  $myWorkSheet = new PHPExcel_Worksheet($this -> xlsobj, 'My Data');
        if ($this -> excelHeaders) {

            foreach ($this -> excelHeaders[$keyInput][$keyInput] as $key => $value) {
                $this -> xlsobj -> getActiveSheet() -> setCellValueByColumnAndRow($key, 1, $value, 'text');
            }
        }
        $this -> xlsobj ->getActiveSheet()->getStyle('A1:B1')->getFont() -> setBold(true) ->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE)) ;
        $this -> xlsobj ->getActiveSheet()->getStyle('A1:B1')-> getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('009688');

        foreach ($this -> prn[$keyInput] as $key => $value) {
            foreach ($value as $keycontainer => $valuecontainer) {
                $this -> xlsobj -> getActiveSheet() -> setCellValueByColumnAndRow($keycontainer, ($key + 2), $valuecontainer, 'text');
            }

        }
        $this -> xlsobj->getActiveSheet()->setAutoFilter(
            $this -> xlsobj->getActiveSheet()->calculateWorksheetDimension()
        );


    }

    private function genSheet() {
        $this -> xlsobj = PHPExcel_IOFactory::createWriter($this -> xlsobj, 'Excel2007');
        $filename = ($this -> xlsName) ? $this -> xlsName . 'xlsx' : str_replace('.php', '', strtotime('now')) . '.xlsx';
        $this -> xlsobj -> save($filename);
        return $filename;
    }

}

/*** include the database controller class ***/
include 'config.php';

if(isset($_GET['current']))
    $result = mysql_query("SELECT phone,adddate FROM phoneDatabase WHERE phone != 0 and adddate = '".date('d-M-Y',strtotime('now'))."'");
else if($_GET['fromDate']){
    $from_date = $_GET['fromDate'];
    $result = mysql_query("SELECT phone,adddate FROM phoneDatabase WHERE phone != 0 and adddate >= '".date('d-M-Y',strtotime($from_date))."'");
}
else
    $result = mysql_query("SELECT phone,adddate FROM phoneDatabase WHERE phone != 0 ");
$phones = array();
while($row = mysql_fetch_assoc($result))
{
    $phones[] = array($row['phone'],$row['adddate'] );
}

$printTer = new ExcelPrinter( array($phones), array( array( array('Phone', 'Scrape Date'))), array('kleinanzeigen.ebay.de scraper'), 1);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename='.$printTer -> savedFileName);
header('Pragma: no-cache');
readfile($printTer -> savedFileName);
unlink($printTer -> savedFileName);
