<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\EventListener;

use Darvin\FileBundle\Entity\AbstractFile;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * Rename file event subscriber
 */
class RenameSubscriber implements EventSubscriber
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * @var \Vich\UploaderBundle\Storage\StorageInterface
     */
    private $uploaderStorage;

    /**
     * @var string
     */
    private $tmpDir;

    /**
     * @param \Symfony\Component\Filesystem\Filesystem      $filesystem      Filesystem
     * @param \Vich\UploaderBundle\Storage\StorageInterface $uploaderStorage Uploader storage
     * @param string                                        $tmpDir          Temporary file directory
     */
    public function __construct(Filesystem $filesystem, StorageInterface $uploaderStorage, string $tmpDir)
    {
        $this->filesystem = $filesystem;
        $this->uploaderStorage = $uploaderStorage;
        $this->tmpDir = $tmpDir;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    /**
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $args Event arguments
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $uow = $args->getEntityManager()->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof AbstractFile) {
                continue;
            }

            $changeSet = $uow->getEntityChangeSet($entity);

            if (!isset($changeSet['name'])) {
                continue;
            }

            $pathname    = $this->uploaderStorage->resolvePath($entity, AbstractFile::PROPERTY_FILE);
            $tmpPathname = $this->generateTmpPathname();

            $this->filesystem->rename($pathname, $tmpPathname, true);

            $filename = implode('.', [$entity->getName(), $entity->getExtension()]);

            $entity->setFile(new UploadedFile($tmpPathname, $filename, null, null, true));
        }
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    private function generateTmpPathname(): string
    {
        if (!is_dir($this->tmpDir) && !mkdir($this->tmpDir, 0777, true)) {
            throw new \RuntimeException(sprintf('Unable to create temporary files directory "%s".', $this->tmpDir));
        }

        $pathname = @tempnam($this->tmpDir, '');

        if (false === $pathname) {
            throw new \RuntimeException(sprintf('Unable to create temporary file for renamed file in "%s".', $this->tmpDir));
        }

        return $pathname;
    }
}
