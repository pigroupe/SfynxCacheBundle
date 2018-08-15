<?php
/**
 * This file is part of the <Cache> project.
 *
 * @uses CacheClientInterface
 * @subpackage   Cache
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CacheBundle\Manager\Client;

use Sfynx\CacheBundle\Manager\Generalisation\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Client interface for Redis servers
 *
 * @uses CacheInterface
 * @subpackage   Cache
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RediscacheClient implements ClientInterface
{
    /** @var null */
    protected $path = null;
    /** @var null */
    protected $options = null;
    /** @var null|CacheItemPoolInterface */
    protected $redis = null;
    /** @var array */
    protected $servers = [];
    /** @var float */
    protected $sockttl = 0.2;
    /** @var bool */
    protected $compression = false;

    /**
     * RediscacheClient constructor.
     *
     * @param CacheItemPoolInterface $redis
     */
    public function __construct(CacheItemPoolInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $cacheItem = $this->redis->getItem($key);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = 3600)
    {
        $cacheItem = $this->redis->getItem($key);
        $cacheItem->set($value);

        if (0 > $ttl) {
            $cacheItem->expiresAfter($ttl);
        }

        return $this->redis->save($cacheItem);
    }

    /**
     * {@inheritdoc}
     */
    public function fresh($key, $value = null)
    {
        return $this->redis->set($key, $value, $this->compression);
    }

    /**
     * {@inheritdoc}
     */
    public function isSafe($key = null)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear($key)
    {
        return $this->redis->deleteItem($key);
    }

    /**
     * {@inheritdoc}
     */
    public function globClear(string $pattern = ''): bool
    {
        return $this->redis->clear();
    }
}