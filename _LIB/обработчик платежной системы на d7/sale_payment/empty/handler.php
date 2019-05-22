<?php

namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Config;
use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Main\Result;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Sale\Order;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PriceMaths;


Loc::loadMessages(__FILE__);


class EmptyHandler extends PaySystem\ServiceHandler// implements PaySystem\IRefundExtended, PaySystem\IHold
{

/**

 * @param Payment $payment
 * @param Request|null $request
 * @return PaySystem\ServiceResult
 */

public function initiatePay(Payment $payment, Request $request = null)

{
    $params = array( );

    $this->setExtraParams($params);

    return $this->showTemplate($payment, "template");

}

/**

 * @param Payment $payment

 * @param Request $request

 * @return PaySystem\ServiceResult

 */

public function processRequest(Payment $payment, Request $request)

{

    $result = new PaySystem\ServiceResult();
    return $result;

}


/**
 * @param Request $request
 * @return mixed
 */

public function getPaymentIdFromRequest(Request $request)

{
    $paymentId = $request->get('ORDER');
    $paymentId = preg_replace("/^[0]+/","",$paymentId);
    return intval($paymentId);
}


/**
 * @return array
 */
public function getCurrencyList()
{
    return array('RUB');
}


/**
 * @param Request $request
 * @param $paySystemId
 * @return bool
 */

static protected function isMyResponseExtended(Request $request, $paySystemId)
{
    return true;
}


}