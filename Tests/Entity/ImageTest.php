<?php

namespace Acme\DemoBundle\Tests\Utility;

use Sweet\GalleryBundle\Entity\Image;

/**
 * Image Entity Test
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Image::toArray
     */
    public function testToArray()
    {
        $image = new Image();
        $result = $image->toArray();

        $this->assertArrayHasKey('id', $result, 'has id');
        $this->assertArrayHasKey('gallery_id', $result, 'has gallery_id');
        $this->assertArrayHasKey('name', $result, 'has name');
        $this->assertArrayHasKey('file', $result, 'has filename');

        $this->assertCount(5, $result, 'Should have exact number of array keys');
    }
}