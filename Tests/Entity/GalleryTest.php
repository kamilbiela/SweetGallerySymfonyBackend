<?php

namespace Acme\DemoBundle\Tests\Utility;

use Sweet\GalleryBundle\Entity\Gallery;
use Sweet\GalleryBundle\Entity\Image;

/**
 * Image Entity Test
 */
class GalleryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Gallery::toArrayList
     */
    public function testToArrayListHasKeys()
    {
        $gallery = new Gallery();

        $result = $gallery->toArrayList();

        $this->assertArrayHasKey('id', $result, 'has id');
        $this->assertArrayHasKey('name', $result, 'has name');
        $this->assertArrayHasKey('images', $result, 'has images');
    }

    /**
     * @covers Gallery::toArrayList
     */
    public function testToArrayListImages()
    {
        $gallery = new Gallery();
        $result = $gallery->toArrayList();
        $this->assertCount(0, $result['images'], 'Should not have any images');

        $gallery->addImage(new Image());
        $result = $gallery->toArrayList();
        $this->assertCount(1, $result['images'], 'Should have one image');

        $gallery->addImage(new Image())->addImage(new Image());

        $result = $gallery->toArrayList();
        $this->assertCount(1, $result['images'], 'Should have max one image');
    }
}