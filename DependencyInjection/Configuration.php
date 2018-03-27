<?php
namespace Sfynx\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\FactoryPassInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @category   Sfynx\CacheBundle
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfynx_cache');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $this->addSessionSupportSection($rootNode);
        $this->addSerializerSection($rootNode);
        $this->addValidationSection($rootNode);
        $this->addAnnotationSection($rootNode);
        $this->addDoctrineSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Normalizes the enabled field to be truthy.
     *
     * @param NodeDefinition $node
     *
     * @return Configuration
     */
    private function normalizeEnabled(ArrayNodeDefinition $node, string $index)
    {
        $node->beforeNormalization()
            ->always()
            ->then(
                function ($root) use ($index) {
                    if (isset($root[$index])) {
                        $v = $root[$index];
                        if (is_string($v['enabled'])) {
                            $v['enabled'] = $v['enabled'] === 'true';
                        }
                        if (is_int($v['enabled'])) {
                            $v['enabled'] = $v['enabled'] === 1;
                        }
                    }
                    return $root;
                }
            )
            ->end();
        return $this;
    }

    /**
     * Configure the "cache.session" section.
     *
     * @return ArrayNodeDefinition
     */
    private function addSessionSupportSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('session')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->booleanNode('use_tagging')->defaultTrue()->end()
                        ->scalarNode('prefix')->defaultValue('session_')->end()
                        ->scalarNode('ttl')->end()
                        ->scalarNode('provider')->defaultValue('')->end()
                        ->scalarNode('factory')->defaultValue('')->end()
                        ->arrayNode('factory_options')
                            ->children()
                                ->arrayNode('parameters')->prototype('scalar')->end()->defaultValue([])->end()
                                ->arrayNode('options')->prototype('scalar')->end()->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
            ->end();
        $this->normalizeEnabled($rootNode, FactoryPassInterface::HANDLER_SESSION);

        return $rootNode;
    }

    /**
     * Configure the "cache.serializer" section.
     *
     * @return ArrayNodeDefinition
     */
    private function addSerializerSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('serializer')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->booleanNode('use_tagging')->defaultTrue()->end()
                        ->scalarNode('prefix')->defaultValue('')->end()
                        ->scalarNode('provider')->defaultValue('')->end()
                        ->scalarNode('factory')->defaultValue('')->end()
                        ->arrayNode('factory_options')
                            ->children()
                                ->arrayNode('parameters')->prototype('scalar')->end()->defaultValue([])->end()
                                ->arrayNode('options')->prototype('scalar')->end()->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
            ->end();
        $this->normalizeEnabled($rootNode, FactoryPassInterface::HANDLER_SERIALIZER);

        return $rootNode;
    }

    /**
     * Configure the "cache.serializer" section.
     *
     * @return ArrayNodeDefinition
     */
    private function addValidationSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('validation')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->booleanNode('use_tagging')->defaultTrue()->end()
                        ->scalarNode('prefix')->defaultValue('')->end()
                        ->scalarNode('provider')->defaultValue('')->end()
                        ->scalarNode('factory')->defaultValue('')->end()
                        ->arrayNode('factory_options')
                            ->children()
                                ->arrayNode('parameters')->prototype('scalar')->end()->defaultValue([])->end()
                                ->arrayNode('options')->prototype('scalar')->end()->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
            ->end();
        $this->normalizeEnabled($rootNode, FactoryPassInterface::HANDLER_VALIDATION);

        return $rootNode;
    }

    /**
     * Configure the "cache.annotation" section.
     *
     * @return ArrayNodeDefinition
     */
    private function addAnnotationSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('annotation')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->booleanNode('use_tagging')->defaultTrue()->end()
                        ->scalarNode('prefix')->defaultValue('')->end()
                        ->scalarNode('provider')->defaultValue('')->end()
                        ->scalarNode('factory')->defaultValue('')->end()
                        ->arrayNode('factory_options')
                            ->children()
                                ->arrayNode('parameters')->prototype('scalar')->end()->defaultValue([])->end()
                                ->arrayNode('options')->prototype('scalar')->end()->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
            ->end();
        $this->normalizeEnabled($rootNode, FactoryPassInterface::HANDLER_ANNOTATION);

        return $rootNode;
    }

    /**
     * Configure the "cache.doctrine" section.
     *
     * @return ArrayNodeDefinition
     */
    private function addDoctrineSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('doctrine')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->arrayNode('metadata')
                            ->canBeUnset()
                            ->children()
                                ->booleanNode('use_tagging')->defaultTrue()->end()
                                ->scalarNode('provider')->defaultValue('')->end()
                                ->scalarNode('factory')->defaultValue('')->end()
                                ->arrayNode('factory_options')
                                    ->children()
                                        ->arrayNode('parameters')->prototype('scalar')->end()->end()
                                        ->arrayNode('options')->prototype('scalar')->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('entity_managers')->prototype('scalar')->end()->defaultValue([])->end()
                                ->arrayNode('document_managers')->prototype('scalar')->end()->defaultValue([])->end()
                            ->end()
                        ->end()
                        ->arrayNode('result')
                            ->canBeUnset()
                            ->children()
                                ->booleanNode('use_tagging')->defaultTrue()->end()
                                ->scalarNode('provider')->defaultValue('')->end()
                                ->scalarNode('factory')->defaultValue('')->end()
                                ->arrayNode('factory_options')
                                    ->children()
                                        ->arrayNode('parameters')->prototype('scalar')->end()->defaultValue([])->end()
                                        ->arrayNode('options')->prototype('scalar')->end()->defaultValue([])->end()
                                    ->end()
                                ->end()
                                ->arrayNode('entity_managers')->prototype('scalar')->end()->defaultValue([])->end()
                                ->arrayNode('document_managers')->prototype('scalar')->end()->defaultValue([])->end()
                            ->end()
                        ->end()
                        ->arrayNode('query')
                            ->canBeUnset()
                            ->children()
                                ->booleanNode('use_tagging')->defaultTrue()->end()
                                ->scalarNode('provider')->defaultValue('')->end()
                                ->scalarNode('factory')->defaultValue('')->end()
                                ->arrayNode('factory_options')
                                    ->children()
                                        ->arrayNode('parameters')->prototype('scalar')->end()->defaultValue([])->end()
                                        ->arrayNode('options')->prototype('scalar')->end()->defaultValue([])->end()
                                    ->end()
                                ->end()
                                ->arrayNode('entity_managers')->prototype('scalar')->end()->defaultValue([])->end()
                                ->arrayNode('document_managers')->prototype('scalar')->end()->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
            ->end();
        $this->normalizeEnabled($rootNode, FactoryPassInterface::HANDLER_DOCTRINE);

        return $rootNode;
    }
}