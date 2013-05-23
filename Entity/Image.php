<?php

namespace Sweet\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sweet\GalleryBundle\Entity\Gallery;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sweet\GalleryBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Uploadable(path="uploads", allowOverwrite=false, appendNumber=true, filenameGenerator="ALPHANUMERIC")
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     * @Gedmo\UploadableFilePath
     * @Assert\Image()
     * @Assert\NotBlank()
     */
    private $file;


    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    private $thumbnail;


    /**
     * @var Gallery
     * 
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="images")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     */
    private $gallery;


    /**
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param \Sweet\GalleryBundle\Entity\Gallery $gallery
     * 
     * @return \Sweet\GalleryBundle\Entity\Image
     */
    public function setGallery(Gallery $gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * 
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set filename
     *
     * @param string $file
     * 
     * @return Image
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Convert object to array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'         => $this->getId(),
            'gallery_id' => $this->getGallery() ? $this->getGallery()->getId() : null,
            'name'       => $this->getName(),
            'file'       => $this->getFile(),
            'thumbnail'  => $this->getThumbnail(),
        );
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Image
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
}
