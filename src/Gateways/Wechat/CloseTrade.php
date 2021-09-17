<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Payment;

/**
 * 关闭交易
 * Class CloseTrade
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class CloseTrade extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'pay/closeorder';

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
        $selfParams = [
            'out_trade_no' => $requestParams['trade_no'] ?? '',
        ];
        return $selfParams;
    }

}
