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
        $this->assertArrayHasKey('name', $result, 'has name');
        $this->assertArrayHasKey('filename', $result, 'has filename');

        $this->assertEquals(3, count($result), 'Should have exact number of array keys');
    }
}