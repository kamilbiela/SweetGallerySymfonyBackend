<?php

namespace Sweet\GalleryBundle\Tests\Controller;

use Sweet\GalleryBundle\Test\WebTestCaseExtended;

/**
 * Gallery Controller tests
 */
class GalleryControllerTest extends WebTestCaseExtended
{
    /**
     * @covers \Sweet\GalleryBundle\Controller\GalleryController::getIndexAction
     */
    public function testGetGalleries()
    {
        $client = static::createClient();
        $client->request('GET', '/galleries');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(5, $response, 'Should return 5 galleries');
        $this->assertArrayHasKey('images', $response[0], 'Should have images key');
        $this->assertCount(0, $response[0]['images'], 'Gallery[0] should have 0 images in response');
        $this->assertCount(1, $response[3]['images'], 'Gallery[1] should have 1 image in response');
    }
}
