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

namespace Darvin\FileBundle\Namer;

use Darvin\FileBundle\Entity\AbstractFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * Directory namer
 */
class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * {@inheritDoc}
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {
        if (!$object instanceof AbstractFile) {
            throw new \InvalidArgumentException(sprintf('Object must be instance of "%s".', AbstractFile::class));
        }

        return implode(DIRECTORY_SEPARATOR, [$object::getBaseUploadDir(), $object::getUploadDir()]);
    }
}
