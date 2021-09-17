<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Payment;

/**
 * 退款查询
 * Class RefundQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class RefundQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'pay/refundquery';

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
            'transaction_id' => $requestParams['transaction_id'] ?? '',
            'out_trade_no' => $requestParams['trade_no'] ?? '',
            'out_refund_no' => $requestParams['refund_no'] ?? '',
            'refund_id' => $requestParams['refund_id'] ?? '',
            'offset' => $requestParams['offset'] ?? '',
        ];
        return $selfParams;
    }

}
