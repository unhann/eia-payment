<?php

namespace EiaComposer\Payment\Contracts;

/**
 * 查询接口
 */
interface IQueryProxy
{

    /**
     * 交易查询
     * @param array $requestParams
     * @return mixed
     */
    public function tradeQuery(array $requestParams);

    /**
     * 退款查询
     * @param array $requestParams
     * @return mixed
     */
    public function refundQuery(array $requestParams);

    /**
     * 转账查询
     * @param array $requestParams
     * @return mixed
     */
    public function transferQuery(array $requestParams);

    /**
     * 账单查询
     * @param array $requestParams
     * @return mixed
     */
    public function billDownload(array $requestParams);

    /**
     * 打款结算查询
     * @param array $requestParams
     * @return mixed
     */
    public function settleDownload(array $requestParams);

}
