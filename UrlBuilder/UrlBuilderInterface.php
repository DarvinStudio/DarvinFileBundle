<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\UrlBuilder;

use Darvin\FileBundle\Entity\AbstractFile;

/**
 * URL builder
 */
interface UrlBuilderInterface
{
    /**
     * @param \Darvin\FileBundle\Entity\AbstractFile|null $file        File
     * @param bool                                        $prependHost Whether to prepend host
     * @param string|null                                 $fallback    Fallback
     *
     * @return string|null
     */
    public function buildOriginalUrl(?AbstractFile $file, bool $prependHost = false, ?string $fallback = null): ?string;

    /**
     * @param \Darvin\FileBundle\Entity\AbstractFile|null $file File
     *
     * @return bool
     */
    public function isActive(?AbstractFile $file): bool;
}
