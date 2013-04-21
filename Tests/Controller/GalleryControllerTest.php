<?php

namespace Sweet\GalleryBundle\Tests\Controller;

use Sweet\GalleryBundle\Tests\BaseController;

/**
 * Gallery Controller tests
 */
class GalleryControllerTest extends BaseController
{
    /**
     * test GET /galleries/
     * @covers \Sweet\GalleryBundle\Controller\GalleryController::getIndexAction
     */
    public function testGetGalleries()
    {
        $client = static::createClient();
        $client->request('GET', '/galleries/');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(count($response), 5, 'Should return 5 galleries');
        $this->assertArrayHasKey('images', $response[0], 'Should have images key');
        $this->assertEquals(count($response[0]['images']), 0, 'Gallery[0] should have 0 images in response');
        $this->assertEquals(count($response[3]['images']), 1, 'Gallery[1] should have 1 image in response');
    }
}
