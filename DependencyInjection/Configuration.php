<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\DependencyInjection;

use Darvin\FileBundle\Entity\AbstractFile;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * File configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('darvin_file');
        $builder->getRootNode()
            ->children()
                ->arrayNode('constraints')->useAttributeAsKey('name')->prototype('variable')->end()->end()
                ->scalarNode('tmp_dir')->defaultValue('%kernel.project_dir%/var/tmp/darvin/file')->cannotBeEmpty()->end()
                ->scalarNode('upload_path')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('action')
                    ->children()
                        ->arrayNode('edit')
                            ->children()
                                ->scalarNode('translation_domain')->defaultValue('darvin_file')->cannotBeEmpty()->end()
                                ->arrayNode('template')->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('full')->defaultValue('@DarvinFile/file/edit.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('partial')->defaultValue('@DarvinFile/file/_edit.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                    ->beforeNormalization()->ifString()->then(function (string $template) {
                                        $parts   = explode('/', $template);
                                        $parts[] = sprintf('_%s', array_pop($parts));

                                        return [
                                            'full'    => $template,
                                            'partial' => implode('/', $parts),
                                        ];
                                    })->end()
                                ->end()
                                ->arrayNode('fields')->useAttributeAsKey('entity')
                                    ->prototype('array')->useAttributeAsKey('name')
                                        ->prototype('array')->canBeDisabled()
                                            ->children()
                                                ->scalarNode('type')->defaultNull()->end()
                                                ->arrayNode('options')->useAttributeAsKey('name')->prototype('variable')->end()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->validate()
                                        ->ifTrue(function (array $config) {
                                            foreach (array_keys($config) as $class) {
                                                if (!class_exists($class)) {
                                                    throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
                                                }
                                                if (AbstractFile::class !== $class && !is_subclass_of($class, AbstractFile::class)) {
                                                    throw new \InvalidArgumentException(sprintf('Class "%s" is not file entity class.', $class));
                                                }
                                            }
                                        })
                                        ->thenInvalid('')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();

        return $builder;
    }
}
