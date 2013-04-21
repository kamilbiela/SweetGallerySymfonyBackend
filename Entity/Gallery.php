<?php

namespace Sweet\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use \Sweet\GalleryBundle\Entity\Image;

/**
 * Gallery
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sweet\GalleryBundle\Entity\GalleryRepository")
 */
class Gallery
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
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="gallery", cascade="all")
     */
    private $images;

    /**
     * Init Gallery
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @param Image $image
     * 
     * @return \Sweet\GalleryBundle\Entity\Gallery
     */
    public function addImage(Image $image)
    {
        $this->images->add($image);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return null|Image
     */
    public function getMainImage()
    {
        return $this->images[0];
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
     * @return Gallery
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
     * Get Gallery representation for list view types (return only one image in images key)
     *
     * @return array
     */
    public function toArrayList()
    {
        $image = $this->getMainImage();
        if ($image) {
            $image = $image->toArray();
        }

        return array(
            'id'     => $this->getId(),
            'name'   => $this->getName(),
            'images' => $image ? array($image) : array(),
        );
    }
}
