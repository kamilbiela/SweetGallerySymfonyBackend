<?php


namespace Sweet\GalleryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sweet\GalleryBundle\Entity\Gallery;
use Sweet\GalleryBundle\Entity\Image;

/**
 * Loads Gallery fixtures
 */
class GalleryData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $gallery = new Gallery();
            $gallery->setName('Gallery name '. $i);

            for ($j = 0; $j < $i; $j++) {
                $image = new Image();
                $image->setName('image '. $j);
                $image->setGallery($gallery);
                $image->setFile('placeholder.png');

                $gallery->addImage($image);
            }

            $manager->persist($gallery);
        }

        $manager->flush();
    }
}