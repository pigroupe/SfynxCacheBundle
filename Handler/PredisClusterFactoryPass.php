<?php
namespace Sfynx\CacheBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Logical\TrueSpec;

use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\FactoryPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\HandlerPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\AbstractHandlerPass;
use Sfynx\CacheBundle\Handler\Util\Pool\FixedTaggingCachePool;
use Sfynx\CacheBundle\Adapter\PredisClusterFactory;

/**
 * Class PredisClusterFactoryPass
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
class PredisClusterFactoryPass extends AbstractHandlerPass
{
    /** @var string */
    const FACTORY_NAME = FactoryPassInterface::FACTORY_RPREDIS_CLUSTER;

    /**
     * @return InterfaceSpecification
     */
    protected function doSpecification(): InterfaceSpecification
    {
        return new TrueSpec();
    }

    /**
     * Prepare object attributs values used by class specifications
     *
     * @param ContainerBuilder $container
     * @return void
     */
    protected function prepareObject(ContainerBuilder $container): void
    {
    }

    /**
     * The process function check if request is valid
     * and do any post treatment (persisting the entity etc.)
     *
     * @return bool
     */
    protected function process(ContainerBuilder $container, array $config): bool
    {
        $container->register(sprintf('sfynx.cache.factory.%s', static::FACTORY_NAME), PredisClusterFactory::class);

        return true;
    }
}
