<?
// Документация по работе с api яндекс.доставки   https://tech.yandex.ru/delivery/doc/dg/about-docpage/

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
CModule::IncludeModule("sale");


/////////////////// Получим все заказы Яндекс.Доставки ///////////////////////

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://delivery.yandex.ru/api/last/getSenderOrders"); // путь для получения списка всех заказов
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$method_key = "358dc157110ce0a7d93571b8d9af208620c91aae5838b016489e204e29faf1aa"; // ключ для получения списка заказов

// ключи для разных методов разные, их можно получить в кабинете яндекс.доставки -> Настройки -> Интеграция -> получить api ключи

$curl_opt = array(
    "client_id" => 1657,
    "sender_id" => 823,
);

ksort($curl_opt);
$secret_string = "";
foreach ($curl_opt as $value)
{
    if (!is_array($value))
        $secret_string .= $value;
    else
    {
        ksort($value);
        foreach ($value as $value2)
        {
            if (!is_array($value2))
                $secret_string .= $value2;
            else
            {
                ksort($value2);
                foreach ($value2 as $value3)
                {
                    if (!is_array($value3))
                        $secret_string .= $value3;
                }
            }
        }
    }
}
$secret_key = md5($secret_string.$method_key);
$curl_opt["secret_key"] = $secret_key;
$query_str = http_build_query($curl_opt);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query_str);

$response = curl_exec($ch);
curl_close($ch);
$response_arr = json_decode($response, true);

$ya_order_ids = array(); // сформируем массив вида ID заказа Битрикс => ID заказа Яндекс.Доставки

foreach ($response_arr['data']['orders'] as $ya_order) {
	// if ($ya_order['num'] == 234948) {
	// 	echo "<pre>";
	// 	print_r($ya_order);
	// 	echo "</pre><hr>";
	// }
	$ya_order_ids[$ya_order['num']] = $ya_order['order_id'];
}

// получим данные заказов и сформируем массив вида ID заказа Битрикс => ID номера службы доставки
$deilvery_nums = array();

foreach ($ya_order_ids as $bx_order_id => $ya_order_id) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://delivery.yandex.ru/api/last/getOrderInfo"); // путь для получения данных заказа
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_POST, TRUE);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$method_key = "358dc157110ce0a7d93571b8d9af208694ade1bd4c6fc90e5162876262f43033"; // ключ для получения данных заказа

	$curl_opt = array(
	    "client_id" => 1657,
	    "sender_id" => 823,
	    "order_id" => $ya_order_id // для получения данных заказа
	);

	ksort($curl_opt);
	$secret_string = "";
	foreach ($curl_opt as $value)
	{
	    if (!is_array($value))
	        $secret_string .= $value;
	    else
	    {
	        ksort($value);
	        foreach ($value as $value2)
	        {
	            if (!is_array($value2))
	                $secret_string .= $value2;
	            else
	            {
	                ksort($value2);
	                foreach ($value2 as $value3)
	                {
	                    if (!is_array($value3))
	                        $secret_string .= $value3;
	                }
	            }
	        }
	    }
	}
	$secret_key = md5($secret_string.$method_key);
	$curl_opt["secret_key"] = $secret_key;
	$query_str = http_build_query($curl_opt);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query_str);

	$response = curl_exec($ch);
	curl_close($ch);
	$response_arr = json_decode($response, true);
	// echo $response_arr['data']['delivery_num'] . "<br>";
	$deilvery_nums[$bx_order_id] = $response_arr['data']['delivery_num'];
	// echo "<hr>";
}
echo "<pre>";
print_r($deilvery_nums);
echo "</pre>";

///////////////// запишем в свойство заказа Идентификатор отправления номер служб доставки

foreach ($deilvery_nums as $bx_id => $deliv_num) {
	$arOrder = CSaleOrder::getByID($bx_id);
	if ($arOrder && $deliv_num) {
		CSaleOrder::update($bx_id, array(
			"TRACKING_NUMBER" => $deliv_num
		));
	}
}

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>