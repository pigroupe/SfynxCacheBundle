<?php
namespace Sfynx\CacheBundle\Handler\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;

/**
 * Class SpecIsDoctrineBridgeExists
 *
 * @category Sfynx\CacheBundle
 * @package Handler
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsDoctrineBridgeExists extends AbstractSpecification
{
    /**
     * return true if the request status is validated
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return class_exists('Cache\Bridge\Doctrine\DoctrineCacheBridge');
    }
}
