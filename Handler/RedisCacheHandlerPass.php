<?php
namespace Sfynx\CacheBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

use Psr\Cache\CacheItemPoolInterface;
use Cache\SessionHandler\Psr6SessionHandler;
use Cache\Taggable\TaggablePSR6PoolAdapter;

use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\CacheBundle\Handler\Specification\SpecIsEnabled;

use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\FactoryPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\HandlerPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\AbstractHandlerPass;
use Sfynx\CacheBundle\Handler\Util\Pool\FixedTaggingCachePool;
use Sfynx\CacheBundle\Handler\Util\Bridge\SessionHandlerBridge;


use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler;

/**
 * Class RedisCacheHandlerPass
 *
 * @category   Bundle
 * @package    Sfynx\CacheBundle
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class RedisCacheHandlerPass extends AbstractHandlerPass
{
    /** @var string */
    const HANDLER_NAME = FactoryPassInterface::HANDLER_CACHE_REDIS;

    /**
     * @return InterfaceSpecification
     */
    protected function doSpecification(): InterfaceSpecification
    {
        return new SpecIsEnabled();
    }

    /**
     * The process function check if request is valid
     * and do any post treatment (persisting the entity etc.)
     *
     * @return bool
     */
    protected function process(ContainerBuilder $container, array $config): bool
    {
        if (!empty($config['provider'])
            && !empty($config['client'])
        ) {
            $id_client = sprintf('sfynx.cache.%s.client', static::HANDLER_NAME);
            $container->register($id_client, $config['client'])
                ->setPublic(true)
                ->addArgument(new Reference($config['provider']));

            if (!empty($config['factory'])) {
                if (\class_exists($config['factory'])) {
                    $container->register(sprintf('sfynx.cache.%s', static::HANDLER_NAME), $config['factory'])
                        ->setPublic(true)
                        ->addArgument(new Reference($id_client));
                }
            }
        }

        return true;
    }
}
