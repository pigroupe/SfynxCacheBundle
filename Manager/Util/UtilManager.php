<?php
namespace Sfynx\CacheBundle\Manager\Util;

use Sfynx\ToolBundle\Builder\PiFileManagerBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of the file manager
 *
 * @category   Sfynx\CacheBundle
 * @package    Manager
 * @subpackage Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class UtilManager
{

    /**
     * Create a directory and all subdirectories needed.
     * @param string $pathname
     * @param octal $mode
     */
    public static function mkdirr($pathname, $mode = null)
    {
        // Check if directory already exists
        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }
        // Ensure a file does not already exist with the same name
        if (is_file($pathname)) {
            return false;
        }
        // Crawl up the directory tree
        $nextPathname = substr($pathname, 0, strrpos($pathname, "/"));
        if (self::mkdirr($nextPathname, $mode)) {
            if (!file_exists($pathname)) {
                if ((null === $mode)) {
                    return mkdir($pathname);
                }
                return mkdir($pathname, $mode);
            }
        }

        return false;
    }
}
