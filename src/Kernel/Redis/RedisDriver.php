<?php
namespace Mine\Kernel\Redis;

use Hyperf\Cache\Driver\Driver;
use Hyperf\Cache\Driver\KeyCollectorInterface;
use Hyperf\Cache\Exception\InvalidArgumentException;
use Hyperf\Redis\RedisFactory;
use Hyperf\Redis\RedisProxy;
use HyperfHelper\Dependency\Annotation\Dependency;
use Mine\Kernel\Tenant\Tenant;
use Psr\Container\ContainerInterface;

class RedisDriver extends Driver implements KeyCollectorInterface
{

    protected RedisProxy $redis;

    public function __construct(ContainerInterface $container, array $config)
    {
        parent::__construct($container, $config);

        $this->redis = $container->get(RedisFactory::class)->get($config['pool_name']);
    }

    protected function getCacheKey(string $key): string
    {
        $tenantId = Tenant::instance()->getId();
        $this->prefix = $this->prefix ?? \Hyperf\Config\config('redis.default.prefix');

        return ($this->prefix . trim($tenantId, ':') . ':' . $key);
    }

    public function get($key, $default = null): mixed
    {
        $res = $this->redis->get($this->getCacheKey($key));
        if ($res === false) {
            return $default;
        }

        return $this->packer->unpack($res);
    }

    public function fetch(string $key, $default = null): array
    {
        $res = $this->redis->get($this->getCacheKey($key));
        if ($res === false) {
            return [false, $default];
        }

        return [true, $this->packer->unpack($res)];
    }

    public function set($key, $value, $ttl = null): bool
    {
        $seconds = $this->secondsUntil($ttl);
        $res = $this->packer->pack($value);
        if ($seconds > 0) {
            return $this->redis->set($this->getCacheKey($key), $res, $seconds);
        }

        return $this->redis->set($this->getCacheKey($key), $res);
    }

    public function delete($key): bool
    {
        return (bool) $this->redis->del($this->getCacheKey($key));
    }

    public function clear(): bool
    {
        return $this->clearPrefix('');
    }

    public function getMultiple($keys, $default = null): iterable
    {
        $cacheKeys = array_map(function ($key) {
            return $this->getCacheKey($key);
        }, $keys);

        $values = $this->redis->mget($cacheKeys);
        $result = [];
        foreach ($keys as $i => $key) {
            $result[$key] = $values[$i] === false ? $default : $this->packer->unpack($values[$i]);
        }

        return $result;
    }

    public function setMultiple($values, $ttl = null): bool
    {
        if (! is_array($values)) {
            throw new InvalidArgumentException('The values is invalid!');
        }

        $cacheKeys = [];
        foreach ($values as $key => $value) {
            $cacheKeys[$this->getCacheKey($key)] = $this->packer->pack($value);
        }

        $seconds = $this->secondsUntil($ttl);
        if ($seconds > 0) {
            foreach ($cacheKeys as $key => $value) {
                $this->redis->set($key, $value, $seconds);
            }

            return true;
        }

        return $this->redis->mset($cacheKeys);
    }

    public function deleteMultiple($keys): bool
    {
        $cacheKeys = array_map(function ($key) {
            return $this->getCacheKey($key);
        }, $keys);

        return (bool) $this->redis->del(...$cacheKeys);
    }

    public function has($key): bool
    {
        return (bool) $this->redis->exists($this->getCacheKey($key));
    }

    public function clearPrefix(string $prefix): bool
    {
        $iterator = null;
        $key = $prefix . '*';
        while (true) {
            $keys = $this->redis->scan($iterator, $this->getCacheKey($key), 10000);
            if (! empty($keys)) {
                $this->redis->del(...$keys);
            }

            if (empty($iterator)) {
                break;
            }
        }

        return true;
    }

    public function addKey(string $collector, string $key): bool
    {
        return (bool) $this->redis->sAdd($this->getCacheKey($collector), $key);
    }

    public function keys(string $collector): array
    {
        return $this->redis->sMembers($this->getCacheKey($collector));
    }

    public function delKey(string $collector, string ...$key): bool
    {
        return (bool) $this->redis->sRem($this->getCacheKey($collector), ...$key);
    }

    public function getConnection(): mixed
    {
        return $this->redis;
    }
}