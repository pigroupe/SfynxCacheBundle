<?php
namespace Sfynx\CacheBundle\Handler\Generalisation\Interfaces;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Cache\AdapterBundle\Factory\AdapterFactoryInterface;

/**
 * Interface HandlerPassInterface
 *
 * @category   Bundle
 * @package    Sfynx\CacheBundle
 * @subpackage Handler\Generalisation\Interfaces
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface HandlerPassInterface
{
    /**
     * @param array|null $options
     */
    public function __construct(array $options = null);

    /**
     * @param ContainerBuilder $container
     * @return bool
     */
    public function execute(ContainerBuilder $container): bool;

    /**
     * @param AdapterFactoryInterface $factory
     * @param array                  $config
     * @param array                  $tags
     *
     * @return DoctrineCacheBridge
     */
    public static function getBridgeFactory(AdapterFactoryInterface $factory, array $config, array $tags);

    /**
     * @param CacheItemPoolInterface $pool
     * @param array                  $config
     * @param array                  $tags
     *
     * @return DoctrineCacheBridge
     */
    public static function getBridge(CacheItemPoolInterface $pool, array $config, array $tags);
}
