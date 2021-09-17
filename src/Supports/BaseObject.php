<?php

namespace EiaComposer\Payment\Supports;

/**
 * 整个lib的基础类
 * Class BaseObject
 * @package EiaComposer\Payment\Supports
 */
abstract class BaseObject
{

    const VERSION = '1.0.0';

    /**
     * @var Config
     */
    public static $config = null;

    /**
     * 获取版本号
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * 获取类名
     * @return string
     */
    public function className()
    {
        return get_called_class();
    }

    /**
     * 设置配置文件
     * @param array $config
     */
    public function setConfig(array $config)
    {
        self::$config = new Config($config);
    }

    /**
     * 项目根路径
     */
    public function getBasePath()
    {
        $path = realpath(dirname(dirname(__FILE__)));
        return $path;
    }

}
