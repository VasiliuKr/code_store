<?
// $_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/krasnov.de-light.ru";
// $DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
// $_SERVER['SERVER_NAME'] = 'www.krasnov.de-light.ru';
// define("NO_KEEP_STATISTIC", true);
// define("NOT_CHECK_PERMISSIONS", true);
// set_time_limit(0);
// define("LANG", "ru");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$file = $_SERVER["DOCUMENT_ROOT"].'/testCSV/small_import-OSTATKI.csv';

/****************************************************************/
/*** IMPORT *****************************************************/
/****************************************************************/

$file_name = $file;
$file_log = $_SERVER["DOCUMENT_ROOT"]."/testCSV/log_skald".date("Y.m.d").".log";

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');

$IBLOCK_ID = 27;

// построчное считывание и анализ строк из файла
if (($handle_f_i = fopen($file_name, "r")) !== FALSE){
	// проверяется, надо ли продолжать импорт с определенного места
	// если да, то указатель перемещается на это место
	if(isset($handle_f_i)){
		fseek($handle_f_i, ftell($handle_f_i));
	}
	$i = 0;
	while ( ($data_f_i = fgetcsv($handle_f_i, 10000000, ";"))!== FALSE) {
		// printr($data_f_i);
		//	из первой строки получаем названия складов и заносим их в свойства товара, если этих свойств нет
		if($i == 0){		
			$arPropsID = array(); //	создаем массив, куда будут заноситься	
			foreach ($data_f_i as $key => $value) {
				if ($key !== 0) {
					$arTranslitParams = array("replace_space"=>"_","replace_other"=>"");
					$code = strtoupper(Cutil::translit($value,"ru",$arTranslitParams));

					$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$code));
					$prop_fields = $properties->GetNext();
					if (empty($prop_fields))
					{
						$arFields = Array(
						  "NAME" => $value,
						  "ACTIVE" => "Y",
						  "SORT" => "500",
						  "CODE" => $code,
						  "PROPERTY_TYPE" => "N",
						  "IBLOCK_ID" => $IBLOCK_ID
						);
						$ibp = new CIBlockProperty;
						$PropID = $ibp->Add($arFields);

						$arPropsID[$key] = $code;
						
					}
					else{
						$arPropsID[$key] = $prop_fields["CODE"];
					}

				}
			}
			
			// printr($arPropsID);
			
		}
		//	для остальных строк разносим количество товара по складам
		else{
			
			$fields = array();
			foreach ($data_f_i as $key => $value) {
				if ($key == 0) {
					$xml_id = $value;
					$res = CIBlockElement::GetList(Array(), Array("XML_ID"=>$xml_id), false, Array(), Array());
					while($ob = $res->GetNextElement())
					{
						$arFields = $ob->GetFields();
						$id = $arFields["ID"];

					}

				} else {
					$fields[$arPropsID[$key]] = $value;
				}
			}

			if($id > 0){
				CIBlockElement::SetPropertyValuesEx(
		            $id,
		            $IBLOCK_ID,
		            $fields
		        );
				unset($id);
			}
		         
		}
		$i++;
	}
	
	fclose($handle_f_i);
}

/****************************************************************/
/*** END IMPORT *************************************************/
/****************************************************************/

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>