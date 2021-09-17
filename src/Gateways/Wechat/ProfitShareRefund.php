<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 对订单进行退款时，如果订单已经分账，可以先调用此接口将指定的金额从分账接收方（仅限商户类型的分账接收方）回退给本商户
 * Class ProfitShareRefund
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class ProfitShareRefund extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'secapi/pay/profitsharingreturn';

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
            'return_account_type' => 'MERCHANT_ID',
            'return_account' => $requestParams['return_account'] ?? '',
            'return_amount' => $requestParams['return_amount'] ?? '',
            'description' => $requestParams['description'] ?? '用户退款',
        ];
        return $selfParams;
    }

}
