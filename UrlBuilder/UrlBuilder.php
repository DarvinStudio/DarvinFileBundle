<?php declare(strict_types=1);
/**
 * @author    Lev Semin     <lev@darvin-studio.ru>
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\UrlBuilder;

use Darvin\FileBundle\Entity\AbstractFile;
use Doctrine\Common\Util\ClassUtils;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * URL builder
 */
class UrlBuilder implements UrlBuilderInterface
{
    /**
     * @var \Vich\UploaderBundle\Storage\StorageInterface
     */
    private $storage;

    /**
     * @var \Darvin\FileBundle\UrlBuilder\UrlAbsolutizerInterface
     */
    private $urlAbsolutizer;

    /**
     * @param \Vich\UploaderBundle\Storage\StorageInterface         $storage        Storage
     * @param \Darvin\FileBundle\UrlBuilder\UrlAbsolutizerInterface $urlAbsolutizer URL absolutizer
     */
    public function __construct(StorageInterface $storage, UrlAbsolutizerInterface $urlAbsolutizer)
    {
        $this->storage = $storage;
        $this->urlAbsolutizer = $urlAbsolutizer;
    }

    /**
     * {@inheritDoc}
     */
    public function buildOriginalUrl(?AbstractFile $file, bool $prependHost = false, ?string $fallback = null): ?string
    {
        if ($this->isActive($file)) {
            return $this->urlAbsolutizer->absolutizeUrl($this->storage->resolveUri($file, AbstractFile::PROPERTY_FILE, ClassUtils::getClass($file)), $prependHost);
        }

        return $this->urlAbsolutizer->absolutizeUrl($fallback, $prependHost);
    }

    /**
     * {@inheritDoc}
     */
    public function isActive(?AbstractFile $file): bool
    {
        return null !== $file && $file->isEnabled();
    }
}
