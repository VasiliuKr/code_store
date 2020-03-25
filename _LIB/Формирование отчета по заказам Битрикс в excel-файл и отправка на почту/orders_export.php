<?
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_STATISTIC', true);
define('STOP_STATISTICS', true);
define('BX_CRONTAB_SUPPORT', true);
define('LANGUAGE_ID', 'ru');

ini_set('memory_limit', '512M');

@set_time_limit(0);
@ignore_user_abort(true);

$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$file = $_SERVER["DOCUMENT_ROOT"] . '/export/orders.xls';

CModule::IncludeModule("sale");
use Bitrix\Sale;


$arFilter = Array(
    ">=DATE_INSERT" => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")),  strtotime('yesterday')),
    "<DATE_INSERT" => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")),  strtotime('today'))
);

$arOrders = array();
$db_sales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter);
$i = 0;
while ($ar_sales = $db_sales->Fetch())
{

    $basket = Sale\Order::load($ar_sales['ID'])->getBasket();
    $basketItems = $basket->getBasketItems();
    $counter = 0;
    foreach ($basket as $basketItem) {
        $productId = $basketItem->getProductId();
        $measure = \Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($productId);
        $arOrders[$i]['ITEMS'][$counter] = $basketItem->getField('NAME') . ' - ' . $basketItem->getQuantity() . ' ' . $measure[$productId]['MEASURE']['SYMBOL_RUS'];
        $counter++;
    }
    $arOrders[$i]['DATE_INSERT_FORMAT'] = $ar_sales['DATE_INSERT_FORMAT'];
    $arOrders[$i]['ID'] = $ar_sales['ID'];
    $arOrders[$i]['PRICE_DELIVERY'] = $ar_sales['PRICE_DELIVERY'];
    $arOrders[$i]['PRICE'] = $ar_sales['PRICE'];
    $i++;
}

//echo "<pre>";
//print_r($arOrders);
//echo "</pre>";
$str = "";
$str .= "<html>
<head>
    <title></title>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <style>
        td {mso-number-format:\@;}
        .number0 {mso-number-format:0;}
        .number2 {mso-number-format:Fixed;}
    </style>
</head>
<body><table border='1'>

    <tr>
        <td>Дата заказа</td>
        <td>Номер заказа</td>
        <td>Стоимость доставки</td>
        <td>Полная стоимость заказа</td>
        <td>Позиции</td>        
    </tr>";

foreach ($arOrders as $order)
{
    $rowspan = count($order['ITEMS']) > 1 ? 'rowspan="' . count($order['ITEMS']) . '"' : '';
    $str .= "
        <tr>
            <td {$rowspan}>{$order['DATE_INSERT_FORMAT']}</td>
            <td {$rowspan}>{$order['ID']}</td>
            <td {$rowspan}>{$order['PRICE_DELIVERY']}</td>
            <td {$rowspan}>{$order['PRICE']}</td>
            <td>{$order['ITEMS'][0]}</td>        
        </tr>
    ";

    if(count($order['ITEMS']) > 1) {
        foreach ($order['ITEMS'] as $key => $value) {
            if($key == 0) continue;
            $str .= "
                <tr>
                    <td>{$value}</td>
                </tr>
            ";
        }
    }

}

$str .= "</table></body></html>";

//echo $str;


$fp = fopen($file, 'w');
fwrite($fp, $str);
fclose($fp);

$arFiles = array($file);
CEvent::Send("ORDERS_EXPORT", 's2', array(), "N", "", $arFiles);

// создать почтовое событие ORDERS_EXPORT и почтовый шаблон
// реализовано на organicmarket.ru