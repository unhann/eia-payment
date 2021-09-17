<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Payment;

/**
 * 申请退款
 * Class Refund
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class Refund extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'secapi/pay/refund';

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
        $totalFee = bcmul($requestParams['total_fee'], 100, 0);
        $refundFee = bcmul($requestParams['refund_fee'], 100, 0);
        $selfParams = [
            'transaction_id' => $requestParams['transaction_id'] ?? '',
            'out_trade_no' => $requestParams['trade_no'] ?? '',
            'out_refund_no' => $requestParams['refund_no'] ?? '',
            'total_fee' => $totalFee,
            'refund_fee' => $refundFee,
            'refund_fee_type' => self::$config->get('fee_type', 'CNY'),
            'refund_desc' => $requestParams['refund_desc'] ?? '',
            'refund_account' => $requestParams['refund_account'] ?? 'REFUND_SOURCE_RECHARGE_FUNDS', // REFUND_SOURCE_UNSETTLED_FUNDS
            'notify_url' => self::$config->get('notify_url', ''),
        ];
        return $selfParams;
    }

}
