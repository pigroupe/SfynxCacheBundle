<?php
namespace Sfynx\CacheBundle\Handler\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;

/**
 * Class SpecIsOdmManagersIsDefined
 *
 * @category Sfynx\CacheBundle
 * @package Handler
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsOdmManagersIsDefined extends AbstractSpecification
{
    /** @var string */
    protected $cacheType;

    /**
     * SpecIsOdmManagersIsDefined constructor.
     * @param string $cacheType
     */
    public function __construct(string $cacheType)
    {
        $this->cacheType = $cacheType;
    }

    /**
     * return true if the request status is validated
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return property_exists($object, 'config')
            && isset($object->config[$this->cacheType]['document_managers'])
            && !empty($object->config[$this->cacheType]['document_managers'])
            ;
    }
}
