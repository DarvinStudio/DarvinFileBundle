<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Controller\File;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * File disable controller
 */
class DisableController extends AbstractFileController
{
    /**
     * @param mixed $id File ID
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke($id): Response
    {
        $file = $this->getFile($id);

        if (!$file->isEnabled()) {
            throw new NotFoundHttpException(sprintf('File with ID "%s" already disabled.', $id));
        }

        $file->setEnabled(false);

        $this->em->flush();

        return new Response();
    }
}
