<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 查询代金券批次
 * Class CouponQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class CouponQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaymkttransfers/querycouponsinfo';

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
            'coupon_id' => $requestParams['coupon_id'] ?? '',
            'openid' => $requestParams['openid'] ?? '',
            'stock_id' => $requestParams['stock_id'] ?? '',
            'op_user_id' => $requestParams['op_user_id'] ?? '',
            'version' => '1.0',
            'type' => 'XML',
        ];
        return $selfParams;
    }

}
