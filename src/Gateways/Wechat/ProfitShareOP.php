<?php

namespace EiaComposer\Payment\Gateways\Wechat;

use EiaComposer\Payment\Contracts\IGatewayRequest;
use EiaComposer\Payment\Exceptions\GatewayException;
use EiaComposer\Payment\Payment;

/**
 * 添加或者删除分账方
 * Class ProfitShareOP
 * @package EiaComposer\Payment\Gateways\Wechat
 */
class ProfitShareOP extends WechatBaseObject implements IGatewayRequest
{

    const ADD_METHOD = 'pay/profitsharingaddreceiver';

    const DEL_METHOD = 'pay/profitsharingremovereceiver';

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
            if (isset($requestParams['mode']) && $requestParams['mode'] === 'del') {
                $url = self::DEL_METHOD;
            } elseif (isset($requestParams['mode']) && $requestParams['mode'] === 'add') {
                $url = self::ADD_METHOD;
            } else {
                throw new GatewayException('please input op mode [add|del]', Payment::PARAMS_ERR);
            }
            return $this->requestWXApi($url, $requestParams);
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
        $receivers = $requestParams['receivers'] ?? '';
        if ($receivers) {
            $receivers = json_encode($receivers);
        } else {
            $receivers = '';
        }
        $selfParams = [
            'receiver' => $requestParams['receivers'] ?? '',
        ];
        return $selfParams;
    }

}
