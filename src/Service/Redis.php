<?php

namespace App\Service;

use Predis\Client;

class Redis
{
    private const TOKEN_PREFIX = 'sf_prof_';
    private const INDEX_NAME = 'index';

    public const REDIS_SERIALIZER_NONE = 0;
    public const REDIS_SERIALIZER_PHP = 1;

    public Client $redis;
    public int $lifetime;

    public function __construct(Client $redis, int $lifetime = 86400)
    {
        $this->redis = $redis;
        $this->lifetime = $lifetime;


    }

    public function getItemName(string $token): string
    {
        $name = $this->prefixKey($token);

        if ($this->isItemNameValid($name)) {
            return $name;
        }

        return '';
    }

    public function getIndexName(): string
    {
        $name = $this->prefixKey(self::INDEX_NAME);

        if ($this->isItemNameValid($name)) {
            return $name;
        }

        return '';
    }

    /**
     * Check if the item name is valid.
     *
     * @throws \RuntimeException
     */
    public function isItemNameValid(string $name): bool
    {
        $length = \strlen($name);

        if ($length > 2147483648) {
            throw new \RuntimeException(sprintf('The Redis item key "%s" is too long (%s bytes). Allowed maximum size is 2^31 bytes.', $name, $length));
        }

        return true;
    }

    /**
     * Retrieves an item from the Redis server.
     */
    public function getValue(string $key, int $serializer = self::REDIS_SERIALIZER_NONE)
    {
        $value = $this->redis->get($key);

        if ($value && (self::REDIS_SERIALIZER_PHP === $serializer)) {
            $value = unserialize($value);
        }

        return $value;
    }

    /**
     * Stores an item on the Redis server under the specified key.
     *
     * @return bool
     */
    public function setValue(string $key, $value, int $expiration = 0, int $serializer = self::REDIS_SERIALIZER_NONE)
    {
        if (self::REDIS_SERIALIZER_PHP === $serializer) {
            $value = serialize($value);
        }
        return $this->redis->setex($key, $expiration, $value);
    }

    /**
     * Appends data to an existing item on the Redis server.
     *
     * @param string $value
     *
     * @return bool
     */
    public function appendValue(string $key, $value, int $expiration = 0)
    {
        if ($this->redis->exists($key)) {
            $this->redis->append($key, $value);

            return $this->redis->expire($key, $expiration);
        }

        return $this->redis->setex($key, $expiration, $value);
    }

    /**
     * Removes the specified keys.
     */
    public function delete(array $keys): bool
    {
        return (bool) $this->redis->del($keys);
    }

    /**
     * Prefixes the key.
     */
    public function prefixKey(string $key): string
    {
        return self::TOKEN_PREFIX.$key;
    }
}
