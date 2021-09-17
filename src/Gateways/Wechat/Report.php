<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 交易保障，上报时间
 * Class Report
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class Report extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'payitil/report';

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
            'device_info' => $requestParams['device_info'] ?? '',
            'interface_url' => $requestParams['interface_url'] ?? '',
            'user_ip' => $requestParams['user_ip'] ?? '',
            'trades' => $requestParams['trades'] ?? '',
        ];
        return $selfParams;
    }

}
