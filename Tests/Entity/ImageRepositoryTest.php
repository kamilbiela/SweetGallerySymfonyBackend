<?php

namespace Sweet\GalleryBundle\Tests\Entity;

use Sweet\GalleryBundle\Test\WebTestCaseExtended;
use Sweet\GalleryBundle\Entity\ImageRepository;

/**
 * Tests for ImageRepository
 */
class ImageRepositoryTest extends WebTestCaseExtended
{
    /**
     * @covers ImageRepository::findAllInGallery
     */
    public function testFindAllInGallery()
    {
        $results = $this->em->getRepository('SweetGalleryBundle:Image')
                ->findAllInGallery(1);
        $this->assertCount(0, $results, 'Gallery 1 should have 0 images');

        $results = $this->em->getRepository('SweetGalleryBundle:Image')
                ->findAllInGallery(3);
        $this->assertCount(2, $results, 'Gallery 3 should have 2 images');
    }
}