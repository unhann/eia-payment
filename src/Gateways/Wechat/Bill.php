<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Payment;

/**
 * 下载账单
 * Class Bill
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class Bill extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'pay/downloadbill';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        try {
            $data = $this->requestWXApi(self::METHOD, $requestParams);
            return $this->formatBill($data);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 格式化账单
     * @param string $data
     * @return array
     */
    protected function formatBill(string $data)
    {
        $fields = [
            'payment_time', 'app_id', 'mch_id', 'sub_mch_id', 'device_id', 'trade_no', 'out_trade_no', 'open_id', 'trade_type', 'trade_status', 'bank',
            'fee_type', 'request_amount', 'coupon_amount', 'refund_no', 'out_refund_no', 'refund_amount', 'recharge_coupon_amount', 'refund_type',
            'refund_status', 'goods_name', 'attach', 'service_fee', 'service_tax', 'order_amount', 'apply_refund_amount', 'tax_mark',
        ];
        $result = [];
        $tmpArr = explode(PHP_EOL, $data);
        foreach ($tmpArr as $index => $item) {
            if ($index === 0) {
                continue;
            }
            $tmpItem = explode(',', $item);
            $tmpResult = [];
            foreach ($fields as $field) {
                $value = current($tmpItem);
                $tmpResult[$field] = trim($value, '`');
                next($tmpItem);
            }
            if (empty($tmpResult)) {
                continue;
            }
            unset($tmpItem);
            $result[] = $tmpResult;
        }
        return $result;
    }

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getSelfParams(array $requestParams)
    {
        $selfParams = [
            'bill_date' => $requestParams['bill_date'] ?? '',
            'bill_type' => $requestParams['bill_type'] ?? 'ALL',
            //'tar_type'  => $requestParams['tar_type'] ?? '',
        ];
        return $selfParams;
    }

}
