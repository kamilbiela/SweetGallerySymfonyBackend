<?php

namespace Sweet\GalleryBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends EntityRepository
{
    /**
     * Find all images in given gallery
     *
     * @param int $galleryId
     * 
     * @return array|null
     */
    public function findAllInGallery($galleryId)
    {
        return $this->findByGallery($galleryId);
    }
}
