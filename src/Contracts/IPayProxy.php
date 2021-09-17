<?php

namespace EiaComposer\Payment\Contracts;

/**
 * 支付接口
 */
interface IPayProxy
{

    /**
     * 支付操作
     * @param string $channel
     * @param array $requestParams
     * @return mixed
     */
    public function pay(string $channel, array $requestParams);

    /**
     * 退款操作
     * @param array $requestParams
     * @return mixed
     */
    public function refund(array $requestParams);

    /**
     * 异步通知
     * @param IPayNotify $callback
     * @return mixed
     */
    public function notify(IPayNotify $callback);

    /**
     * 取消交易
     * @param array $requestParams
     * @return mixed
     */
    public function cancel(array $requestParams);

    /**
     * 关闭交易
     * @param array $requestParams
     * @return mixed
     */
    public function close(array $requestParams);

}
