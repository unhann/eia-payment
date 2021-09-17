<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 企业付款到银行卡API
 * Class TransferBank
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class TransferBank extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaysptrans/pay_bank';

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getSelfParams(array $requestParams)
    {
        $totalFee = bcmul($requestParams['amount'], 100, 0);
        $selfParams = [
            'partner_trade_no' => $requestParams['trans_no'] ?? '',
            'enc_bank_no' => $requestParams['enc_bank_no'] ?? '',
            'enc_true_name' => $requestParams['enc_true_name'] ?? '',
            'bank_code' => $requestParams['bank_code'] ?? '',
            'amount' => $totalFee,
            'desc' => $requestParams['desc'] ?? '',
        ];
        return $selfParams;
    }

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

}
