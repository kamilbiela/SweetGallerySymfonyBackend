<?php

namespace Sweet\GalleryBundle\Tests\Controller;

use Sweet\GalleryBundle\Test\WebTestCaseExtended;
use Sweet\GalleryBundle\Entity\Image;

/**
 * Tests for ImageController
 */
class ImageControllerTest extends WebTestCaseExtended
{
    /**
     * @covers \Sweet\GalleryBundle\Controller\ImageController::getIndexAction
     */
    public function testGetImages()
    {
        $client = static::createClient();
        $client->request('GET', '/galleries/3/images');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(2, $response, 'Should return 2 images');
        $this->assertArrayHasKey('id', $response[0], 'Should have id key');
        $this->assertArrayHasKey('name', $response[0], 'Should have name key');
        $this->assertArrayHasKey('file', $response[0], 'Should have file key');
    }

    /**
     * @covers \Sweet\GalleryBundle\Controller\ImageController::getGalleryAction
     */
    public function testGetImage()
    {
        $client = static::createClient();
        $client->request('GET', '/images/1');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $response, 'Should have id key');
        $this->assertArrayHasKey('name', $response, 'Should have name key');
        $this->assertArrayHasKey('file', $response, 'Should have file key');
    }

    /**
     * @covers \Sweet\GalleryBundle\Controller\ImageController::getGalleryAction
     */
    public function testGetImage404()
    {
        $client = static::createClient();
        $client->request('GET', '/images/1234');

        $this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Should receive 404 response');
    }

    /**
     * @covers \Sweet\GalleryBundle\Controller\ImageController::postAddImageAction
     */
    public function testPostAddImage()
    {
        $filename = '640x480.png';
        $uploadedFilepath = $this->getUploadDir() . DIRECTORY_SEPARATOR . $filename;
        $client = static::createClient();

        $client->request(
            'POST',
            '/galleries/1/images',
            array(
                'name' => 'Image Name',
            ),
            array(
                'file' => $this->getFileFixture($filename),
            )
        );

        // check http response status
        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Should receive 201 response');

        // check if file uploaded
        $this->assertFileEquals(
            $this->getFileFixturePath() . DIRECTORY_SEPARATOR . $filename,
            $uploadedFilepath
        );
        unlink($uploadedFilepath);

        // check if image has right value in 'file' attribute
        $image = $this->em->getRepository('SweetGalleryBundle:Image')
            ->find(11);
        $this->assertEquals('uploads/' . $filename, $image->getFile());

    }
}
