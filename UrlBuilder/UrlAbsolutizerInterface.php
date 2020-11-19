<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\UrlBuilder;

/**
 * URL absolutizer
 */
interface UrlAbsolutizerInterface
{
    /**
     * @param string|null $url         URL
     * @param bool        $prependHost Whether to prepend host
     *
     * @return string|null
     */
    public function absolutizeUrl(?string $url, bool $prependHost = true): ?string;
}
