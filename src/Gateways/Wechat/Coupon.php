<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 发放代金券
 * Class Coupon
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class Coupon extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaymkttransfers/send_coupon';

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
            'coupon_stock_id' => $requestParams['coupon_stock_id'] ?? '',
            'openid_count' => '1', // openid记录数（目前支持num=1）
            'partner_trade_no' => $requestParams['partner_trade_no'] ?? '',
            'openid' => $requestParams['openid'] ?? '',
            'op_user_id' => $requestParams['op_user_id'] ?? '',
            'device_info' => $requestParams['device_info'] ?? '',
            'version' => '1.0',
            'type' => 'XML',
        ];
        return $selfParams;
    }

}
