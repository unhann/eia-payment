<?php

namespace EiaComposer\Payment\Gateways\CMBank;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 查询具体某笔交易的退款状态
 * Class RefundQuery
 * @package EiaComposer\Payment\Gateways\CMBank
 */
class RefundQuery extends CMBaseObject implements IGatewayRequest
{

    const METHOD = 'NetPayment/BaseHttp.dll?QuerySettledRefundV2';

    const SANDBOX_METHOD = 'netpayment_dl/BaseHttp.dll?QuerySettledRefundV2';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        $method = self::METHOD;
        $this->gatewayUrl = 'https://payment.ebank.cmbchina.com/%s';
        if ($this->isSandbox) {
            $method = self::SANDBOX_METHOD;
            $this->gatewayUrl = 'http://121.15.180.66:801/%s';
        }
        try {
            return $this->requestCMBApi($method, $requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getRequestParams(array $requestParams)
    {
        $nowTime = time();
        $params = [
            'dateTime' => date('YmdHis', $nowTime),
            'branchNo' => self::$config->get('branch_no', ''),
            'merchantNo' => self::$config->get('mch_id', ''),
            'type' => $requestParams['type'] ?? 'A',
            'orderNo' => $requestParams['trade_no'] ?? '',
            'date' => date('Ymd', $requestParams['date'] ?? $nowTime),
            'merchantSerialNo' => $requestParams['refund_no'] ?? '',
            'bankSerialNo' => $requestParams['bank_serial_no'] ?? '',
        ];
        return $params;
    }

}
