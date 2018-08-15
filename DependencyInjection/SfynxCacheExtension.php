<?php
/**
 * This file is part of the <Cache> project.
 *
 * @category   Cache
 * @package    DependencyInjection
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CacheBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\FactoryPassInterface;
use Sfynx\CacheBundle\DependencyInjection\Compiler\FactoryPass;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @category   Sfynx\CacheBundle
 * @package    DependencyInjection
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class SfynxCacheExtension extends Extension
{
    const HANDLER_LIST = [
        FactoryPassInterface::HANDLER_SESSION,
        FactoryPassInterface::HANDLER_DOCTRINE,
        FactoryPassInterface::HANDLER_ANNOTATION,
        FactoryPassInterface::HANDLER_SERIALIZER,
        FactoryPassInterface::HANDLER_VALIDATION,
        FactoryPassInterface::HANDLER_CACHE_FILE,
        FactoryPassInterface::HANDLER_CACHE_DUMPER,
        FactoryPassInterface::HANDLER_CACHE_REDIS,
    ];

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        /**
         * Cache config parameter
         */
        foreach (self::HANDLER_LIST as $section) {
            $container->setParameter('sfynx.cache.'.$section, $config[$section]);
        }
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'sfynx_cache';
    }
}
