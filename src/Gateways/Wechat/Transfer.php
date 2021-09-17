<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 企业付款到零钱
 * Class Transfer
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class Transfer extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaymkttransfers/promotion/transfers';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        try {
            return $this->requestWXApi(self::METHOD, $requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getSelfParams(array $requestParams)
    {
        $totalFee = bcmul($requestParams['amount'], 100, 0);
        $selfParams = [
            'device_info' => $requestParams['device_info'] ?? '',
            'partner_trade_no' => $requestParams['trans_no'] ?? '',
            'openid' => $requestParams['openid'] ?? '',
            'check_name' => $requestParams['check_name'] ?? 'FORCE_CHECK',
            're_user_name' => $requestParams['re_user_name'] ?? '',
            'amount' => $totalFee,
            'desc' => $requestParams['desc'] ?? '',
            'spbill_create_ip' => $requestParams['client_ip'] ?? '',
        ];
        return $selfParams;
    }

}
