<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 完结分账，不需要分账的数据
 * Class ProfitShareFinish
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class ProfitShareFinish extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'secapi/pay/profitsharingfinish';

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
            'transaction_id' => $requestParams['transaction_id'] ?? '',
            'out_order_no' => $requestParams['out_order_no'] ?? '',
            'description' => $requestParams['description'] ?? '分账已完成',
        ];
        return $selfParams;
    }

}
