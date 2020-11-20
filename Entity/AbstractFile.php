<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Entity;

use Darvin\FileBundle\Validation\Constraints as DarvinFileAssert;
use Darvin\Utils\File\Size\FileSize;
use Darvin\Utils\File\Size\FileSizeConverter;
use Darvin\Utils\Mapping\Annotation\Clonable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * File
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="file")
 *
 * @Clonable\Clonable(copyingPolicy="ALL")
 *
 * @Vich\Uploadable
 */
abstract class AbstractFile
{
    public const PROPERTY_FILE = 'file';

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var string|null
     *
     * @ORM\Column
     *
     * @Assert\NotBlank(groups={"AdminUpdateProperty"})
     */
    protected $name;

    /**
     * @var string|null
     *
     * @ORM\Column
     */
    protected $extension;

    /**
     * @var string|null
     *
     * @ORM\Column
     */
    protected $filename;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition
     */
    protected $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \Symfony\Component\HttpFoundation\File\File|null
     *
     * @DarvinFileAssert\DarvinFile
     *
     * @Vich\UploadableField(mapping="darvin_file", fileNameProperty="filename")
     */
    protected $file;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->size = 0;
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    abstract public static function getUploadDir(): string;

    /**
     * @return string
     */
    public static function getBaseUploadDir(): string
    {
        return 'files';
    }

    /**
     * @return \Darvin\Utils\File\Size\FileSize
     */
    public function getConvertedSize(): FileSize
    {
        return FileSizeConverter::convert($this->size);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File|null $file file
     *
     * @return AbstractFile
     */
    public function setFile(?File $file): AbstractFile
    {
        if (null !== $file) {
            $this->updatedAt = new \DateTime();
        }

        $this->file = $file;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled enabled
     *
     * @return AbstractFile
     */
    public function setEnabled(bool $enabled): AbstractFile
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name name
     *
     * @return AbstractFile
     */
    public function setName(?string $name): AbstractFile
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string|null $extension extension
     *
     * @return AbstractFile
     */
    public function setExtension(?string $extension): AbstractFile
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename filename
     *
     * @return AbstractFile
     */
    public function setFilename(?string $filename): AbstractFile
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size size
     *
     * @return AbstractFile
     */
    public function setSize(int $size): AbstractFile
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position position
     *
     * @return AbstractFile
     */
    public function setPosition(?int $position): AbstractFile
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt updatedAt
     *
     * @return AbstractFile
     */
    public function setUpdatedAt(\DateTime $updatedAt): AbstractFile
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }
}
