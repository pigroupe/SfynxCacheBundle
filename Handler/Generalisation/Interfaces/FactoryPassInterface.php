<?php
namespace Sfynx\CacheBundle\Handler\Generalisation\Interfaces;

/**
 * Interface FactoryPassInterface
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
interface FactoryPassInterface
{
    /** @var string */
    const HANDLER_ANNOTATION = 'annotation';
    /** @var string */
    const HANDLER_SERIALIZER = 'serializer';
    /** @var string */
    const HANDLER_VALIDATION = 'validation';
    /** @var string */
    const HANDLER_SESSION = 'session';
    /** @var string */
    const HANDLER_DOCTRINE = 'doctrine';
    /** @var string */
    const FACTORY_RPREDIS_CLUSTER = 'predis_cluster';
    /** @var string */
    const HANDLER_CACHE_FILE = 'client_filecache';
    /** @var string */
    const HANDLER_CACHE_DUMPER = 'client_dumpercache';
    /** @var string */
    const HANDLER_CACHE_REDIS = 'client_rediscache';
}
