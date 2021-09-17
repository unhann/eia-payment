<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * Class PublicKeyQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class PublicKeyQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'risk/getpublickey';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        $this->setGatewayUrl('https://fraud.mch.weixin.qq.com/%s');
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
        return [];
    }

}
