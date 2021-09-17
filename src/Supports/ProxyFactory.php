<?php

namespace EiaComposer\Payment\Supports;

use InvalidArgumentException;
use EiaComposer\Payment\Payment;

/**
 * 创建代理的工厂类
 * Class ProxyFactory
 * @package EiaComposer\Payment\Supports
 */
class ProxyFactory
{

    /**
     * @param string $proxyName
     * @return BaseObject
     * @throws InvalidArgumentException
     */
    public static function createProxy(string $proxyName)
    {
        $className = self::formatProxyClassName($proxyName);
        try {
            $proxy = self::makeProxy($className);
        } catch (InvalidArgumentException $e) {
            throw $e;
        }
        return $proxy;
    }

    /**
     * 格式化支付代理的名称
     * @param string $proxyName
     * @return string
     */
    protected static function formatProxyClassName(string $proxyName)
    {
        $name = ucfirst(str_replace(['-', '_', ''], '', $proxyName));
        return "EiaComposer\\Payment\\Proxies\\{$name}Proxy";
    }

    /**
     * @param string $className
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected static function makeProxy(string $className)
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf('Proxy [%s] not exists.', $className), Payment::CLASS_NOT_EXIST);
        }
        return new $className();
    }

}
