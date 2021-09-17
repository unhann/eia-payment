<?php

namespace EiaComposer\Payment\Contracts;

/**
 * 转账交易接口
 */
interface ITransferProxy
{

    /**
     * 转账
     * @param array $requestParams
     * @return mixed
     */
    public function transfer(array $requestParams);

}
