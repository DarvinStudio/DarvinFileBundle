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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * File exterminate controller
 */
class ExterminateController extends AbstractFileController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request): Response
    {
        $exterminated = [];
        $ids          = $request->request->get('ids', []);

        if (!is_array($ids)) {
            $ids = [$ids];
        }
        foreach ($this->getFiles($ids) as $file) {
            $this->em->remove($file);

            $exterminated[] = $file->getId();
        }

        $this->em->flush();

        return new JsonResponse([
            'exterminated' => $exterminated,
        ]);
    }
}
