<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Payment;

/**
 * 下载资金账单
 * Class Settlement
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class Settlement extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'pay/downloadfundflow';

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
            $data = $this->requestWXApi(self::METHOD, $requestParams);
            return $this->formatBill($data);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * @param string $data
     * @return array
     */
    protected function formatBill(string $data)
    {
        $fields = [
            'book_time', 'trade_no', 'amount_bill_bo', 'business_name', 'business_type',
            'income_type', 'income_amount', 'account_balance', 'operator', 'mark', 'business_no',
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
            'account_type' => $requestParams['bill_type'] ?? 'Operation',
            //'tar_type'     => $requestParams['tar_type'] ?? '',
        ];
        return $selfParams;
    }

}
