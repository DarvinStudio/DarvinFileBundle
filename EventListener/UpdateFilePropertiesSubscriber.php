<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\EventListener;

use Darvin\FileBundle\Entity\AbstractFile;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
 * Update file properties event subscriber
 */
class UpdateFilePropertiesSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::POST_UPLOAD => 'updateProperties',
        ];
    }

    /**
     * @param \Vich\UploaderBundle\Event\Event $event Event
     */
    public function updateProperties(Event $event): void
    {
        $file = $event->getObject();

        if (!$file instanceof AbstractFile) {
            return;
        }

        $file->setExtension(strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION)));

        if (null === $file->getName()) {
            $file->setName(preg_replace(sprintf('/\.%s$/', $file->getExtension()), '', $file->getFilename()));
        }
    }
}
