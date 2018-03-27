<?php
namespace Sfynx\CacheBundle\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Cache\PredisCache;

use Cache\AdapterBundle\Factory\AbstractDoctrineAdapterFactory;
use Cache\Adapter\Doctrine\DoctrineCachePool;
use Cache\Adapter\Predis\PredisCachePool;
use Cache\Namespaced\NamespacedCachePool;


use Predis;
//use Predis\Client;
//use Predis\Connection\Aggregate\PredisCluster;
//use Predis\Connection\Factory;
//use Predis\Connection\Parameters;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class PredisClusterFactory extends AbstractDoctrineAdapterFactory
{
    protected static $dependencies = [
        ['requiredClass' => 'Cache\Adapter\Doctrine\DoctrineCachePool', 'packageName' => 'cache/doctrine-adapter'],
        ['requiredClass' => 'Predis\Client', 'packageName' => 'predis/predis'],
    ];

    /**
     * {@inheritdoc}
     * @link https://github.com/nrk/predis/releases
     * @link https://github.com/nrk/predis
     */
    public function getAdapter(array $config)
    {
        $client = new Predis\Client($config['parameters'], [
            'exceptions'  => true,
            'connections' => [
                'tcp'  => 'Predis\Connection\PhpiredisStreamConnection',
            ],
            'distributor' => function () {
                return new Predis\Cluster\Distributor\KetamaRing();
            },
            'strategy'    => function ($options) {
                return new Predis\Cluster\PredisStrategy($options->distributor);
            },
            'cluster'     => function ($options) {
                $strategy = $options->strategy;
                $cluster = new Predis\Connection\Aggregate\PredisCluster($strategy);

                return $cluster;
            },
//            'replication' => function () {
//                // Set scripts that won't trigger a switch from a slave to the master node.
//                $strategy = new Predis\Replication\ReplicationStrategy();
//                $strategy->setScriptReadOnly($LUA_SCRIPT);
//
//                return new Predis\Connection\Aggregate\MasterSlaveReplication($strategy);
//            },
//            'profile'     => function ($options, $option) {
//                $profile = $options->getDefault($option);
//                $profile->defineCommand("luascript", "Nrk\Command\LuaScriptCommand");
//
//                return $profile;
//            },
        ]);

        return new DoctrineCachePool(new PredisCache($client));
    }

    /**
     * {@inheritdoc}
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'host'   => '127.0.0.1',
//            'port'   => '6379',
//            'scheme' => 'tcp',
            'parameters' => [],
            'options' => []
        ]);
//        $resolver->setAllowedTypes('host', ['string']);
//        $resolver->setAllowedTypes('port', ['string', 'int']);
//        $resolver->setAllowedTypes('scheme', ['string']);
        $resolver->setAllowedTypes('parameters', ['array']);
        $resolver->setAllowedTypes('options', ['array']);
    }
}
