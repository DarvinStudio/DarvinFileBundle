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

use Darvin\FileBundle\Entity\AbstractFile;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * File controller abstract implementation
 */
abstract class AbstractFileController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em Entity manager
     */
    public function setEntityManager(EntityManager $em): void
    {
        $this->em = $em;
    }

    /**
     * @param array $ids File IDs
     *
     * @return \Darvin\FileBundle\Entity\AbstractFile[]
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getFiles(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $qb = $this->getFileRepository()->createQueryBuilder('o');

        $files = $qb
            ->where($qb->expr()->in('o.id', $ids))
            ->orderBy('o.position')
            ->getQuery()
            ->getResult();

        if (count($files) !== count($ids)) {
            throw new NotFoundHttpException('Unable to find one or more files.');
        }

        return $files;
    }

    /**
     * @param mixed $id File ID
     *
     * @return \Darvin\FileBundle\Entity\AbstractFile
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getFile($id): AbstractFile
    {
        $file = $this->getFileRepository()->find($id);

        if (null === $file) {
            throw new NotFoundHttpException(sprintf('Unable to find file by ID "%s".', $id));
        }

        return $file;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getFileRepository(): EntityRepository
    {
        return $this->em->getRepository(AbstractFile::class);
    }
}
