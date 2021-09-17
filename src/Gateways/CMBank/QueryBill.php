<?php

namespace EiaComposer\Payment\Gateways\CMBank;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 查询入账明细: 查询商户入账明细，商户系统应以招行入账明细为准进行对账，对账不平的交易进行退款或请款协商。
 * Class QueryBill
 * @package EiaComposer\Payment\Gateways\CMBank
 */
class QueryBill extends CMBaseObject implements IGatewayRequest
{

    const METHOD = 'NetPayment/BaseHttp.dll?QueryAccountListV2';

    const SANDBOX_METHOD = 'NetPayment_dl/BaseHttp.dll?QueryAccountListV2';

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
            'date' => date('Ymd', $requestParams['date'] ?? $nowTime),
            'operatorNo' => $requestParams['operator_id'] ?? '',
            'nextKeyValue' => $requestParams['next_key_value'] ?? '', // 首次查询填“空”; 后续查询，按应答报文中返回的nextKeyValue值原样传入.
        ];
        return $params;
    }

}
