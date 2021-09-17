<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 发放裂变红包
 * Class GroupRedPack
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class GroupRedPack extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'mmpaymkttransfers/sendgroupredpack';

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getSelfParams(array $requestParams)
    {
        $totalFee = bcmul($requestParams['amount'], 100, 0);
        $selfParams = [
            'mch_billno' => $requestParams['bill_no'] ?? '',
            'send_name' => $requestParams['send_name'] ?? '',
            're_openid' => $requestParams['re_openid'] ?? '', // 接受红包的用户openid
            'total_amount' => $totalFee,
            'total_num' => $requestParams['total_num'] ?? '',
            'amt_type' => $requestParams['amt_type'] ?? 'ALL_RAND',
            'wishing' => $requestParams['wishing'] ?? '',
            'act_name' => $requestParams['act_name'] ?? '',
            'remark' => $requestParams['remark'] ?? '',
            'scene_id' => $requestParams['scene_id'] ?? '', // 发放红包使用场景，红包金额大于200或者小于1元时必传
            'risk_info' => $requestParams['risk_info'] ?? '',
            'client_ip' => $requestParams['client_ip'] ?? '',
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
