<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 回退交易的查询
 * Class ProfitShareRefundQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class ProfitShareRefundQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'pay/profitsharingreturnquery';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        $this->setSignType(self::SIGN_TYPE_SHA);
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
            'order_id' => $requestParams['order_id'] ?? '',
            'out_order_no' => $requestParams['out_order_no'] ?? '',
            'out_return_no' => $requestParams['out_return_no'] ?? '',
        ];
        return $selfParams;
    }

}
