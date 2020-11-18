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
                ->scalarNode('tmp_dir')->defaultValue('%kernel.project_dir%/var/tmp/darvin/file')->cannotBeEmpty()->end()
                ->scalarNode('upload_path')->isRequired()->cannotBeEmpty();

        return $builder;
    }
}
