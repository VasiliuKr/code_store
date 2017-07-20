<?
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/krasnov.de-light.ru";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
$_SERVER['SERVER_NAME'] = 'www.krasnov.de-light.ru';
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
define("LANG", "ru");
echo "string";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
echo "string 2";
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule('highloadblock');
function getMenuHL($id){
	$hlblock = HL\HighloadBlockTable::getById($id)->fetch();
	$entity = HL\HighloadBlockTable::compileEntity($hlblock);
	$main_query = new \Bitrix\Main\Entity\Query($entity);
	$main_query->setSelect(array('*'));
	$main_query->setFilter(array());
	$main_query->setOrder(array('UF_SORT' => 'ASC'));
	$result = $main_query->exec();
	$result = new CDBResult($result);
	$list = array();
	while($row = $result->Fetch()){
		$list[$row['ID']] = $row;
		if($row['UF_PARENT'] != "0"){
			$list[$row['ID']] = $row;
			$list[$row['UF_PARENT']]["IS_PARENT"] = "Y";
			$list[$row['ID']]["DEPT_LEVEL"] = getLevel($list["ITEMS"], $row);
		}else
			$list[$row['ID']]["DEPT_LEVEL"] = 0;
	}
	return $list;
}

$site_catalog = "https://www.de-light.ru/catalog/";
$light_list = getMenuHL(9);
$furniture_list = getMenuHL(11);
$accessories_list = getMenuHL(12);

foreach ($light_list as $key => $value) {
	$light_list_links[] = $value['UF_XML_ID'];
}
foreach ($furniture_list as $key => $value) {
	$furniture_list_links[] = $value['UF_XML_ID'];
}
foreach ($accessories_list as $key => $value) {
	$accessories_list_links[] = $value['UF_XML_ID'];
}

$doc = new DOMDocument('1.0', 'utf-8');
$urlset = $doc->createElement("urlset");
$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$doc->appendChild($urlset);

foreach ($light_list_links as $value) {
	$url = $doc->createElement("url");
	$urlset->appendChild($url);

	$loc = $doc->createElement("loc", $site_catalog."light/".$value."/");
	$lastmod = $doc->createElement("lastmod", date('Y-m-dTH:i:sP', time()));
	$url->appendChild($loc);
	$url->appendChild($lastmod);
}

foreach ($furniture_list_links as $value) {
	$url = $doc->createElement("url");
	$urlset->appendChild($url);

	$loc = $doc->createElement("loc", $site_catalog."furniture/".$value."/");
	$lastmod = $doc->createElement("lastmod", date('Y-m-dTH:i:sP', time()));
	$url->appendChild($loc);
	$url->appendChild($lastmod);
}

foreach ($accessories_list_links as $value) {
	$url = $doc->createElement("url");
	$urlset->appendChild($url);

	$loc = $doc->createElement("loc", $site_catalog."accessories/".$value."/");
	$lastmod = $doc->createElement("lastmod", date('Y-m-dTH:i:sP', time()));
	$url->appendChild($loc);
	$url->appendChild($lastmod);
}

$doc->save($_SERVER["DOCUMENT_ROOT"]."/sitemap_categories.xml"); // Сохраняем полученный XML-документ в файл

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>