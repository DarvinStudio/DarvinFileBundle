<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Twig\Extension;

use Darvin\FileBundle\UrlBuilder\UrlBuilderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * URL builder Twig extension
 */
class UrlBuilderExtension extends AbstractExtension
{
    /**
     * @var \Darvin\FileBundle\UrlBuilder\UrlBuilderInterface
     */
    private $urlBuilder;

    /**
     * @param \Darvin\FileBundle\UrlBuilder\UrlBuilderInterface $urlBuilder URL builder
     */
    public function __construct(UrlBuilderInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('file_original', [$this->urlBuilder, 'buildOriginalUrl']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('file_active', [$this->urlBuilder, 'isActive']),
        ];
    }
}
