<?php
namespace Sfynx\CacheBundle\Handler\Generalisation;

use stdClass;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Cache\AdapterBundle\Factory\AdapterFactoryInterface;

use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\HandlerPassInterface;

/**
 * Abstract Class AbstractHandlerPass
 *
 * @category   Bundle
 * @package    Sfynx\CacheBundle
 * @subpackage Handler\Generalisation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * @abstract
 */
abstract class AbstractHandlerPass implements HandlerPassInterface
{
    /** @var stdClass */
    protected $object;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = null)
    {
        $this->options = $options;
        $this->object = new stdClass();
        $this->object->config = [];
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ContainerBuilder $container): bool
    {
        $this->prepareObject($container);
        if (!$this->doSpecification()->isSatisfiedBy($this->object)) {
            return false;
        }
        if (!$this->process($container, $this->object->config)) {
            throw new \InvalidArgumentException('provider is do not an CacheItemPoolInterface interface ');
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function getBridgeFactory(AdapterFactoryInterface $factory, array $config, array $tags)
    {
        $poolAdapter = $factory->createAdapter($config['factory_options']);

        return static::getBridge($poolAdapter, $config, $tags);
    }

    /**
     * {@inheritdoc}
     */
    public static function getBridge(CacheItemPoolInterface $pool, array $config, array $tags) {}

    /**
     *
     * @return InterfaceSpecification
     */
    abstract protected function doSpecification(): InterfaceSpecification;

    /**
     * @param ContainerBuilder $container
     * @return bool
     */
    abstract protected function process(ContainerBuilder $container, array $config): bool;

    /**
     * Prepare object attributs values used by class specifications
     *
     * @param ContainerBuilder $container
     * @return void
     */
    protected function prepareObject(ContainerBuilder $container): void
    {
        $this->object->config = $container->getParameter('sfynx.cache.' . static::HANDLER_NAME);
    }

    /**
     * @param ContainerBuilder $container
     * @param string $bridgeServiceId
     * @param string $cacheType
     * @param string $type
     * @param array $managers
     */
    protected function registerAliasManager(
        ContainerBuilder $container,
        string $bridgeServiceId,
        string $cacheType,
        string $type,
        array $managers
    ) {
        foreach ($managers as $manager) {
            $doctrineDefinitionId =
                sprintf(
                    'doctrine.%s.%s_%s_cache',
                    ($type === 'entity_managers' ? 'orm' : 'odm'),
                    $manager,
                    $cacheType
                );
            // Replace the doctrine entity manager cache with our bridge
            $container->setAlias($doctrineDefinitionId, $bridgeServiceId);
        }
    }
}
