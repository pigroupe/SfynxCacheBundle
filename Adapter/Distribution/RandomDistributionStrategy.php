<?php
namespace Sfynx\CacheBundle\Adapter;

use Predis\Cluster\StrategyInterface;
use Predis\Cluster\Hash\HashGeneratorInterface;

/**
 * This distribution strategy will simply return a random connection
 */
class RandomDistributionStrategy implements StrategyInterface, HashGeneratorInterface
{
    /**
     * @var array
     */
    private $_nodes;
    /**
     * @var int
     */
    private $_nodesCount;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_nodes = [];
        $this->_nodesCount = 0;
    }
    /**
     * {@inheritDoc}
     */
    public function add($node, $weight = null)
    {
        $this->_nodes[] = $node;
        $this->_nodesCount++;
    }
    /**
     * {@inheritDoc}
     */
    public function remove($node)
    {
        $this->_nodes = array_filter($this->_nodes, function($n) use($node) {
            return $n !== $node;
        });
        $this->_nodesCount = count($this->_nodes);
    }
    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (0 === $this->_nodesCount) {
            throw new \OutOfBoundsException('No connections.');
        }
        return $this->_nodes[array_rand($this->_nodes)];
    }
    /**
     * {@inheritDoc}
     */
    public function getHashGenerator()
    {
        return $this;
    }
    /**
     * {@inheritDoc}
     */
    public function hash($value)
    {
        return true; // the key is irrelevant for random distribution
    }
}