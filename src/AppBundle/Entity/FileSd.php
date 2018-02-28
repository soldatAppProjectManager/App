<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 *
 * @ORM\Table(name="sd_file")
 * @ORM\Entity()
 */
class FileSd
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="original_name", length=128)
     * @Assert\NotBlank
     */
    private $originalName;

    /**
     * @ORM\Column(type="string", name="directoty_path", length=128, nullable=true)
     */
    private $directotPath;

    /**
     * @ORM\Column(type="string", name="uuid", length=128, nullable=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", name="full_path", length=128, nullable=true)
     */
    private $fullPath;

    /**
     * @ORM\Column(type="string", name="mime_type", length=128, nullable=true)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="integer", name="size")
     */
    private $size;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "image/*"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     */
    private $file;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    public function upload($directory)
    {
        if (null === $this->file or $directory === null) {
            return;
        }


        $this->uuid = uniqid() . '.' . $this->file->guessExtension();
        $this->originalName = $this->file->getClientOriginalName();
        $this->directotPath = $directory;
        $this->fullPath = $this->getDirectotPath() . '/' . $this->uuid;
        $this->mimeType = $this->getFile()->getMimeType();
        $this->size = $this->file->getSize();


        $this->file->move($directory, $this->uuid);

        unset($this->file);
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     *
     * @return FileSd
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set directotPath
     *
     * @param string $directotPath
     *
     * @return FileSd
     */
    public function setDirectotPath($directotPath)
    {
        $this->directotPath = $directotPath;

        return $this;
    }

    /**
     * Get directotPath
     *
     * @return string
     */
    public function getDirectotPath()
    {
        return $this->directotPath;
    }

    /**
     * Set fullPath
     *
     * @param string $fullPath
     *
     * @return FileSd
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;

        return $this;
    }

    /**
     * Get fullPath
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return FileSd
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return FileSd
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     *
     * @return FileSd
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}
