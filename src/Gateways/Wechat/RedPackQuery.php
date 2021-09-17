<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 查询红包记录
 * Class RedPackQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class RedPackQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaymkttransfers/gethbinfo';

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
            'mch_billno' => $requestParams['bill_no'] ?? '',
            'bill_type' => $requestParams['bill_type'] ?? '',
        ];
        return $selfParams;
    }

}
