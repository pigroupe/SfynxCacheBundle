<?php
namespace Sfynx\CacheBundle\DependencyInjection\Compiler;

use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\FactoryPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\HandlerPassInterface;
use Sfynx\CacheBundle\Handler\AnnotationHandlerPass;
use Sfynx\CacheBundle\Handler\SerializerHandlerPass;
use Sfynx\CacheBundle\Handler\ValidationHandlerPass;
use Sfynx\CacheBundle\Handler\SessionHandlerPass;
use Sfynx\CacheBundle\Handler\DoctrineHandlerPass;
use Sfynx\CacheBundle\Handler\RedisCacheHandlerPass;
use Sfynx\CacheBundle\Handler\PredisClusterFactoryPass;

/**
 * Class FactoryPass
 *
 * @category   Bundle
 * @package    Sfynx\CacheBundle
 * @subpackage DependencyInjection\Compiler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class FactoryPass implements FactoryPassInterface
{
    protected static $handler = [
        self::HANDLER_ANNOTATION => AnnotationHandlerPass::class,
        self::HANDLER_SERIALIZER => SerializerHandlerPass::class,
        self::HANDLER_VALIDATION => ValidationHandlerPass::class,
        self::HANDLER_SESSION => SessionHandlerPass::class,
        self::HANDLER_DOCTRINE => DoctrineHandlerPass::class,
        self::HANDLER_CACHE_REDIS => RedisCacheHandlerPass::class,
        self::FACTORY_RPREDIS_CLUSTER => PredisClusterFactoryPass::class,
    ];

    /**
     * Create the class that will change the factory class regarding the provider type.
     *
     * @param string $name
     * @param array|null $options
     * @return HandlerPassInterface
     */
    public static function create($name, array $options = null)
    {
        if (array_key_exists($name, self::$handler)) {
            return new self::$handler[$name]($options);
        }
        throw new \InvalidArgumentException(sprintf('Could not execute the "%s" handler name', $name));
    }
}
