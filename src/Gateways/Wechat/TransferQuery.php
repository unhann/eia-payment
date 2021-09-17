<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 查询企业付款
 * Class TransferQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class TransferQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaymkttransfers/gettransferinfo';

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
            'partner_trade_no' => $requestParams['trans_no'] ?? '',
        ];
        return $selfParams;
    }

}
