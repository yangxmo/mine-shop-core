<?php

namespace Mine\Cache;

use Hyperf\Config\Annotation\Value;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MineCache extends \Mine\Abstracts\AbstractRedis
{
    #[value('redis.default.prefix')]
    protected ?string $prefix;

    protected string $typeName = 'config';

    /**
     * @param string $key
     * @param array $params
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function setDictCache(string $key, mixed $params): void
    {
        $key = $this->getKey('dict:' . $key);
        $this->redis()->set($key, $params);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getDictCache(string $key): mixed
    {
        $key = $this->getKey('dict:' . $key);
        return $this->redis()->get($key);
    }


    /**
     * @param string $key
     * @param array $params
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function setUploadCache(string $key, mixed $params): void
    {
        $key = $this->getKey('upload:' . $key);
        $this->redis()->set($key, $params);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getUploadCache(string $key): mixed
    {
        $key = $this->getKey('upload:' . $key);
        return $this->redis()->get($key);
    }

    /**
     * @param string $key
     * @param array $params
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function setUploadGroupCache(string $key, mixed $params): void
    {
        $key = $this->getKey('uploadGroup:' . $key);
        $this->redis()->set($key, $params);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getUploadGroupCache(string $key): mixed
    {
        $key = $this->getKey('uploadGroup:' . $key);
        return $this->redis()->get($key);
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function delUploadOrUploadGroup(): void
    {
        $key = $this->getKey('upload:*');
        $groupKey = $this->getKey('uploadGroup:*');

        $keyCache = $this->redis()->keys($key);

        $groupCache = $this->redis()->keys($groupKey);

        foreach ($groupCache as $item) {
            $this->redis()->del($item);
        }

        foreach ($keyCache as $item) {
            $this->redis()->del($item);
        }
    }

    /**
     * @param array $params
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function setCrontabCache(mixed $params): void
    {
        $key = $this->getKey('crontab');
        $this->redis()->set($key, $params);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getCrontabCache(): mixed
    {
        $key = $this->getKey('crontab');
        return $this->redis()->get($key);
    }

    /**
     * @return false|int|\Redis
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function delCrontabCache()
    {
        $key = $this->getKey('crontab');
        return $this->redis()->del($key);
    }


    /**
     * @param mixed $params
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function setModuleCache(mixed $params): void
    {
        $key = $this->getKey('modules');
        $this->redis()->set($key, $params);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getModuleCache(): mixed
    {
        $key = $this->getKey('modules');
        return $this->redis()->get($key);
    }

    /**
     * @return false|int|\Redis
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function delModuleCache()
    {
        $key = $this->getKey('modules');
        return $this->redis()->del($key);
    }

    /**
     * @param array $params
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function setSensitiveWordsCache(mixed $params): void
    {
        $key = $this->getKey('sensitiveWords');
        $this->redis()->set($key, $params);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getSensitiveWordsCache(): mixed
    {
        $key = $this->getKey('sensitiveWords');
        return $this->redis()->get($key);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getSensitiveWordsCacheExist(): bool
    {
        $key = $this->getKey('sensitiveWords');
        return $this->redis()->exists($key);
    }

    /**
     * @param array $params
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function rPushSensitiveWordsCache(array $params): void
    {
        $key = $this->getKey('sensitiveWords');
        $this->redis()->rPush($key, $params);
    }

    /**
     * @param $iterator
     * @param $key
     * @param int $limit
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function scan(&$iterator, $key, int $limit = 100)
    {
        $key = $this->getKey($key);
        return $this->redis()->scan($iterator, $key, $limit);
    }

    /**
     * @param $iterator
     * @param $key
     * @param int $limit
     * @return string|null
     */
    public function getScanKey(&$iterator, $key, int $limit = 100)
    {
        return $this->getKey($key);
    }

    /**
     * @param $key
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function delScanKey($key)
    {
        $this->redis()->del($key);
    }

    /**
     * @param $key
     * @return array|false|\Redis
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getKeys($key)
    {
        return $this->redis()->keys($key);
    }
}