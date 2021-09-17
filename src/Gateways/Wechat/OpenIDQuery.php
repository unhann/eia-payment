<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 查询openid
 * Class OpenIDQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class OpenIDQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'tools/authcodetoopenid';

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
            'auth_code' => $requestParams['auth_code'] ?? '', // 扫码支付授权码，设备读取用户微信中的条码或者二维码信息
        ];
        return $selfParams;
    }

}
