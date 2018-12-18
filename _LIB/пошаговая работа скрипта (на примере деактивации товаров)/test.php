<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Деактивация товаров");
?>
<?

define(ITEMS_STEP, 200);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

if(!CModule::IncludeModule("catalog"))
{
   ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
   return;
}

if(!$_REQUEST["STEP"]) {
  $step=1;
} else {
  $step=$_REQUEST["STEP"];
}

$arNavParams = array("iNumPage" => $step, 'nPageSize' => ITEMS_STEP);
$arSort = array("SORT" => "ASC");
$arSelect = array("ID");
$arLoadProductArray = Array( "ACTIVE" => "N"); 
$counter = 0;
$arrr = array();

$db_res = CCatalogProduct::GetList(
  array("SORT"=>"ASC"),
  array("QUANTITY" => 0, "ELEMENT_IBLOCK_ID" => 25),
  false,
  $arNavParams,
  array("ID", "ELEMENT_IBLOCK_ID")
);
$countItems = ceil($db_res->SelectedRowsCount() / ITEMS_STEP);

while ($ar_res = $db_res->Fetch())
{   
  $arrr[] = $ar_res['ID'];
}
echo "<pre>";
print_r(count($arrr));
echo "</pre>";
$arFilter = array("IBLOCK_ID" => 25, "ID" => $arrr, "ACTIVE" => "Y");
$itemsList = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
if ($itemsList->SelectedRowsCount() > 0) {
  while ($item = $itemsList->Fetch()) {
    $el = new CIBlockElement; 
    if($res = $el->Update($item['ID'], $arLoadProductArray)) {
      AddMessage2Log($item['ID'] . " deactivated \r\n" );
    }
    $counter++;
  }
}

echo "<pre>";
print_r($counter);
echo "</pre>";

$step++;
?>

<?if($countItems >= $step):?>
  <script>
  $(document).ready(function () {
    setTimeout(function() {           
      location.replace("/test.php?STEP=<?=$step?>");
    }, 1000); 

  });
  </script>
<?endif?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>