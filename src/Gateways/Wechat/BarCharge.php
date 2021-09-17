<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Payment;

/**
 * 付款码支付
 * Class BarCharge
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class BarCharge extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'pay/micropay';

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
        $limitPay = self::$config->get('limit_pay', '');
        if ($limitPay) {
            $limitPay = $limitPay[0];
        } else {
            $limitPay = '';
        }
        $nowTime = time();
        $timeExpire = intval($requestParams['time_expire']);
        if (!empty($timeExpire)) {
            $timeExpire = date('YmdHis', $timeExpire);
        } else {
            $timeExpire = date('YmdHis', $nowTime + 1800); // 默认半小时过期
        }
        $receipt = $requestParams['receipt'] ?? false;
        $totalFee = bcmul($requestParams['amount'], 100, 0);
        $sceneInfo = $requestParams['scene_info'] ?? '';
        if ($sceneInfo) {
            $sceneInfo = json_encode(['store_info' => $sceneInfo]);
        } else {
            $sceneInfo = '';
        }
        $selfParams = [
            'device_info' => $requestParams['device_info'] ?? '',
            'body' => $requestParams['subject'] ?? '',
            'detail' => $requestParams['body'] ?? '',
            'attach' => $requestParams['return_param'] ?? '',
            'out_trade_no' => $requestParams['trade_no'] ?? '',
            'total_fee' => $totalFee,
            'fee_type' => self::$config->get('fee_type', 'CNY'),
            'spbill_create_ip' => $requestParams['client_ip'] ?? '',
            'goods_tag' => $requestParams['goods_tag'] ?? '',
            'limit_pay' => $limitPay,
            'time_start' => date('YmdHis', $nowTime),
            'time_expire' => $timeExpire,
            'receipt' => $receipt === true ? 'Y' : '',
            'auth_code' => $requestParams['auth_code'] ?? '',
            'scene_info' => $sceneInfo,
        ];
        return $selfParams;
    }

}
