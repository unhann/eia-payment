<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 查询企业付款银行卡
 * Class TransferBankQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class TransferBankQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaysptrans/query_bank';

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
