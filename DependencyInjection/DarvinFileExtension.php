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

use Darvin\Utils\DependencyInjection\ConfigInjector;
use Darvin\Utils\DependencyInjection\ConfigLoader;
use Darvin\Utils\DependencyInjection\ExtensionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * File extension
 */
class DarvinFileExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        (new ConfigInjector($container))->inject($this->processConfiguration(new Configuration(), $configs), $this->getAlias());

        (new ConfigLoader($container, __DIR__.'/../Resources/config/services'))->load([
            'archive/core',
            'namer',
            'orphan',

            'archive/archiver/zip' => ['extension' => 'zip'],
            'archive/common'       => ['extension' => 'zip'],
        ]);

        if (extension_loaded('zip')) {
            $container->setAlias('darvin_file.archiver', 'darvin_file.archiver.zip');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        (new ExtensionConfigurator($container, __DIR__.'/../Resources/config/app'))->configure('vich_uploader');
    }
}
