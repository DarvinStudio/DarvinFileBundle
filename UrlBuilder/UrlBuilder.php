<?php declare(strict_types=1);
/**
 * @author    Lev Semin     <lev@darvin-studio.ru>
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\UrlBuilder;

use Darvin\FileBundle\Entity\AbstractFile;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * URL builder
 */
class UrlBuilder implements UrlBuilderInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var \Vich\UploaderBundle\Storage\StorageInterface
     */
    private $storage;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack Request stack
     * @param \Vich\UploaderBundle\Storage\StorageInterface  $storage      Storage
     */
    public function __construct(RequestStack $requestStack, StorageInterface $storage)
    {
        $this->requestStack = $requestStack;
        $this->storage = $storage;
    }

    /**
     * {@inheritDoc}
     */
    public function buildOriginalUrl(?AbstractFile $file, bool $prependHost = false): ?string
    {
        if (!$this->isActive($file)) {
            return null;
        }

        return $this->makeUrlAbsolute($this->storage->resolveUri($file, AbstractFile::PROPERTY_FILE, ClassUtils::getClass($file)), $prependHost);
    }

    /**
     * {@inheritDoc}
     */
    public function isActive(?AbstractFile $file): bool
    {
        return null !== $file && $file->isEnabled();
    }

    /**
     * @param string|null $url         URL
     * @param bool        $prependHost Whether to prepend host
     *
     * @return string|null
     */
    private function makeUrlAbsolute(?string $url, bool $prependHost = true): ?string
    {
        if (null === $url) {
            return null;
        }

        $parts = ['/', ltrim($url, '/')];

        if ($prependHost) {
            $request = $this->requestStack->getCurrentRequest();

            if (null !== $request) {
                array_unshift($parts, $request->getSchemeAndHttpHost());
            }
        }

        return implode('', $parts);
    }
}
