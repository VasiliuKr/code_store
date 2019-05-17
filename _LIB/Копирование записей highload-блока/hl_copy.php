<?

// Поля в новом hl-блоке необходимо предварительно создать

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$hlbl = 5; // HL Colors
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    /*"filter" => array("UF_PRODUCT_ID"=>"77","UF_TYPE"=>'33')*/  // Задаем параметры фильтра выборки
));

$hlbl_copy = 19; // Указываем ID нашего highloadblock блока куда скопируем данные.
$hlblock_copy = HL\HighloadBlockTable::getById($hlbl_copy)->fetch();

$entity_copy = HL\HighloadBlockTable::compileEntity($hlblock_copy);
$entity_data_class_copy = $entity_copy->getDataClass();


while($arData = $rsData->Fetch()){
    /*echo "<pre>";
    print_r($arData);
    echo "</pre>";*/
    $result = $entity_data_class_copy::add($arData); // копируем записи в HL блок 19
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>