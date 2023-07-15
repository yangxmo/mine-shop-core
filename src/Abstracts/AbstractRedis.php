<?php
/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://gitee.com/xmo/MineAdmin
 */

declare (strict_types = 1);
namespace Mine\Abstracts;

use Hyperf\Redis\Redis;
use Mine\Kernel\Tenant\Tenant;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class AbstractRedis
 * @package Mine\Abstracts
 */
abstract class AbstractRedis
{
    /**
     * 缓存前缀
     */
    protected ?string $prefix = '';

    /**
     * key 类型名
     */
    protected string $typeName;

    /**
     * 获取实例
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getInstance()
    {
        return container()->get(static::class);
    }

    /**
     * 获取redis实例
     * @param string $poolName
     * @return Redis
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function redis(string $poolName = 'default'): Redis
    {
        return redis($poolName);
    }

    /**
     * 获取key
     * @param string $key
     * @return string|null
     */
    public function getKey(string $key): ?string
    {
        $tenantId = Tenant::instance()->getId() . ':';
        $this->prefix = $this->prefix ?? \Hyperf\Config\config('redis.default.prefix');

        return empty($key) ? null : ($this->prefix . $tenantId . trim($this->typeName, ':') . ':' . $key);
    }

}