<?php

namespace EiaComposer\Payment\Proxies;

use InvalidArgumentException;
use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Contracts\IPayNotify;
use EiaComposer\Payment\Contracts\IPayProxy;
use EiaComposer\Payment\Contracts\IQueryProxy;
use EiaComposer\Payment\Contracts\ITransferProxy;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Gateways\Wechat\Bill;
use EiaComposer\Payment\Gateways\Wechat\CancelTrade;
use EiaComposer\Payment\Gateways\Wechat\CloseTrade;
use EiaComposer\Payment\Gateways\Wechat\Notify;
use EiaComposer\Payment\Gateways\Wechat\Refund;
use EiaComposer\Payment\Gateways\Wechat\RefundQuery;
use EiaComposer\Payment\Gateways\Wechat\Settlement;
use EiaComposer\Payment\Gateways\Wechat\TradeQuery;
use EiaComposer\Payment\Gateways\Wechat\Transfer;
use EiaComposer\Payment\Gateways\Wechat\TransferBank;
use EiaComposer\Payment\Gateways\Wechat\TransferBankQuery;
use EiaComposer\Payment\Gateways\Wechat\TransferQuery;
use EiaComposer\Payment\Payment;
use EiaComposer\Payment\Supports\BaseObject;

/**
 * 微信对外暴露的方案集合
 * Class WechatProxy
 * @package EiaComposer\Payment\Proxies
 */
class WechatProxy extends BaseObject implements IPayProxy, IQueryProxy, ITransferProxy
{

    /**
     * 支付操作
     * @param string $channel
     * @param array $requestParams
     * @return mixed
     * @throws \EiaComposer\Payment\Exceptions\GatewayException
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
        return "EiaComposer\\Payment\\Gateways\\Wechat\\{$name}Charge";
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
            $trade = new Refund();
            return $trade->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 异步通知
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
        $flag = $callback->handle('Wechat', $data['notify_type'], 'async', $data['notify_data']);
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
            $trade = new CancelTrade();
            return $trade->request($requestParams);
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
            $trade = new CloseTrade();
            return $trade->request($requestParams);
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
            $trade = new TradeQuery();
            return $trade->request($requestParams);
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
            $trade = new RefundQuery();
            return $trade->request($requestParams);
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
        $channel = $requestParams['channel'] ?? 'bank';
        try {
            if ($channel === 'bank') {
                $trade = new TransferBankQuery();
            } else {
                $trade = new TransferQuery();
            }
            return $trade->request($requestParams);
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
            $trade = new Bill();
            return $trade->request($requestParams);
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
        try {
            $trade = new Settlement();
            return $trade->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 转账
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function transfer(array $requestParams)
    {
        $channel = $requestParams['channel'] ?? 'bank';
        try {
            if ($channel === 'bank') {
                $trf = new TransferBank();
            } else {
                $trf = new Transfer();
            }
            return $trf->request($requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

}
