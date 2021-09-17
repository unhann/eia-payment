<?php

namespace EiaComposer\Payment\Gateways\Alipay;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Helpers\ArrayUtil;
use EiaComposer\Payment\Payment;

/**
 * 为方便商户快速查账，支持商户通过本接口获取商户离线账单下载地址
 * Class Bill
 * @package EiaComposer\Payment\Gateways\Alipay
 */
class Bill extends AliBaseObject implements IGatewayRequest
{

    const METHOD = 'alipay.data.dataservice.bill.downloadurl.query';

    /**
     * trade、signcustomer；trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单
     * @param array $requestParams
     * @return mixed
     */
    protected function getBizContent(array $requestParams)
    {
        $bizContent = [
            'bill_type' => $requestParams['bill_type'] ?? 'trade',
            'bill_date' => $requestParams['bill_date'] ?? '', // 日账单格式为yyyy-MM-dd
        ];
        $bizContent = ArrayUtil::paraFilter($bizContent);
        return $bizContent;
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
            $params = $this->buildParams(self::METHOD, $requestParams);
            $ret = $this->get($this->gatewayUrl, $params);
            $retArr = json_decode($ret, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new GatewayException(sprintf('format bill data get error, [%s]', json_last_error_msg()), Payment::FORMAT_DATA_ERR, ['raw' => $ret]);
            }
            $content = $retArr['alipay_data_dataservice_bill_downloadurl_query_response'];
            if ($content['code'] !== self::REQ_SUC) {
                throw new GatewayException(sprintf('request get failed, msg[%s], sub_msg[%s]', $content['msg'], $content['sub_msg']), Payment::SIGN_ERR, $content);
            }
            $signFlag = $this->verifySign($content, $retArr['sign']);
            if (!$signFlag) {
                throw new GatewayException('check sign failed', Payment::SIGN_ERR, $retArr);
            }
            return $content;
        } catch (GatewayException $e) {
            throw $e;
        }
    }

}
