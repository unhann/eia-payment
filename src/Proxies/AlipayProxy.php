<?php

namespace EiaComposer\Payment\Proxies;

use InvalidArgumentException;
use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Contracts\IPayNotify;
use EiaComposer\Payment\Contracts\IPayProxy;
use EiaComposer\Payment\Contracts\IQueryProxy;
use EiaComposer\Payment\Contracts\ITransferProxy;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Gateways\Alipay\Bill;
use EiaComposer\Payment\Gateways\Alipay\CancelTrade;
use EiaComposer\Payment\Gateways\Alipay\CloseTrade;
use EiaComposer\Payment\Gateways\Alipay\Notify;
use EiaComposer\Payment\Gateways\Alipay\Refund;
use EiaComposer\Payment\Gateways\Alipay\RefundQuery;
use EiaComposer\Payment\Gateways\Alipay\TradeQuery;
use EiaComposer\Payment\Gateways\Alipay\Transfer;
use EiaComposer\Payment\Gateways\Alipay\TransferQuery;
use EiaComposer\Payment\Payment;
use EiaComposer\Payment\Supports\BaseObject;

/**
 * 支付宝的代理类
 * Class AlipayProxy
 * @package EiaComposer\Payment\Proxies
 */
class AlipayProxy extends BaseObject implements IPayProxy, IQueryProxy, ITransferProxy
{

    /**
     * 支付操作
     * @param string $channel
     * @param array $requestParams
     * @return mixed
     * @throws InvalidArgumentException
     * @throws GatewayException
     */
    public function pay(string $channel, array $requestParams)
    {
        $className = $this->getChargeClass($channel);
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf('Gateway [%s] not exists.', $className), Payment::CLASS_NOT_EXIST);
        }
        try {
            /**
             * @var IGatewayRequest $charge
             */
            $charge = new $className();
            return $charge->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 获取支付类
     * @param string $channel
     * @return string
     */
    private function getChargeClass(string $channel)
    {
        $name = ucfirst(str_replace(['-', '_', ''], '', $channel));
        return "EiaComposer\\Payment\\Gateways\\Alipay\\{$name}Charge";
    }

    /**
     * 退款操作
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function refund(array $requestParams)
    {
        try {
            $obj = new Refund();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 同步、异步通知
     * @param IPayNotify $callback
     * @return mixed
     * @throws GatewayException
     */
    public function notify(IPayNotify $callback)
    {
        try {
            $n = new Notify();
            $data = $n->request(); // 获取数据
        } catch (GatewayException $e) {
            throw $e;
        }
        // 异步 async，同步 sync
        $flag = $callback->handle('Alipay', $data['notify_type'], $data['notify_way'], $data['notify_data']);
        return $n->response($flag);
    }

    /**
     * 取消交易
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function cancel(array $requestParams)
    {
        try {
            $obj = new CancelTrade();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 关闭交易
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function close(array $requestParams)
    {
        try {
            $obj = new CloseTrade();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 交易查询
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function tradeQuery(array $requestParams)
    {
        try {
            $obj = new TradeQuery();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 退款查询
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function refundQuery(array $requestParams)
    {
        try {
            $obj = new RefundQuery();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 账单查询
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function billDownload(array $requestParams)
    {
        try {
            $obj = new Bill();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 打款结算查询
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function settleDownload(array $requestParams)
    {
        throw new GatewayException('ali not support the method.', Payment::NOT_SUPPORT_METHOD);
    }

    /**
     * 支付宝到支付宝转账
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function transfer(array $requestParams)
    {
        try {
            $obj = new Transfer();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 转账查询
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function transferQuery(array $requestParams)
    {
        try {
            $obj = new TransferQuery();
            return $obj->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

}
