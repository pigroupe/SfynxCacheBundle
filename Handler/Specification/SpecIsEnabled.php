<?php
namespace Sfynx\CacheBundle\Handler\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;

/**
 * Class SpecIsEnabled
 *
 * @category Sfynx\CacheBundle
 * @package Handler
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsEnabled extends AbstractSpecification
{
    /**
     * return true if the request status is validated
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return property_exists($object, 'config')
            && isset($object->config['enabled'])
            && is_bool($object->config['enabled'])
            && $object->config['enabled']
            ;
    }
}
