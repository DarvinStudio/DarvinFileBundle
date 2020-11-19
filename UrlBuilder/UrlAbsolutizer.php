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

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * URL absolutizer
 */
class UrlAbsolutizer implements UrlAbsolutizerInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack Request stack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     */
    public function absolutizeUrl(?string $url, bool $prependHost = true): ?string
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
