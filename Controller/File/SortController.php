<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Controller\File;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * File sort controller
 */
class SortController extends AbstractFileController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(Request $request): Response
    {
        $ids = $request->request->get('ids');

        if (!is_array($ids)) {
            throw new NotFoundHttpException(sprintf('Query parameter "ids" must be an array, got "%s".', gettype($ids)));
        }
        if (empty($ids)) {
            return new Response();
        }

        /** @var \Darvin\FileBundle\Entity\AbstractFile[] $files */
        $files = $positions = [];

        foreach ($this->getFiles($ids) as $file) {
            $files[$file->getId()] = $file;
            $positions[] = $file->getPosition();
        }
        foreach ($ids as $index => $id) {
            $files[$id]->setPosition($positions[$index]);
        }

        $this->em->flush();

        return new Response();
    }
}
