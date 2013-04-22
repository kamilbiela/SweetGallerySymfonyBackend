<?php

namespace Sweet\GalleryBundle\Tests\Controller;

use Sweet\GalleryBundle\Test\WebTestCaseExtended;

/**
 * Tests for ImageController
 */
class ImageControllerTest extends WebTestCaseExtended
{
    /**
     * @covers \Sweet\GalleryBundle\Controller\GalleryController::getIndexAction
     */
    public function testGetGalleries()
    {
        $client = static::createClient();
        $client->request('GET', '/galleries/3/images');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(2, $response, 'Should return 2 images');
        $this->assertArrayHasKey('id', $response[0], 'Should have id key');
        $this->assertArrayHasKey('name', $response[0], 'Should have name key');
        $this->assertArrayHasKey('filename', $response[0], 'Should have filename key');
    }
}
