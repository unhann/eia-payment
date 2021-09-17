<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;

/**
 * 拉取订单评价数据
 * Class CommentQuery
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class CommentQuery extends WechatBaseObject implements IGatewayRequest
{

    const METHOD = 'billcommentsp/batchquerycomment';

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
            'begin_time' => $requestParams['begin_time'] ?? date('YmdHis', strtotime('-1days')),
            'end_time' => $requestParams['end_time'] ?? date('YmdHis', time()),
            'offset' => $requestParams['offset'] ?? 0,
            'limit' => $requestParams['limit'] ?? 200,
        ];
        return $selfParams;
    }

}
