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

        $result = $gallery->toArray();

        $this->assertArrayHasKey('id', $result, 'has id');
        $this->assertArrayHasKey('name', $result, 'has name');
        $this->assertArrayHasKey('thumbnail', $result, 'has thumbnail');
    }
}