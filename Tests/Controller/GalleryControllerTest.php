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
        $this->assertCount(0, $response[0]['images'], 'Should have 0 images in response');
        $this->assertCount(1, $response[3]['images'], 'Should have 1 image in response');
    }

    /**
     * @covers \Sweet\GalleryBundle\Controller\GalleryController::getIndexAction
     */
    public function testGetGallery()
    {
        $client = static::createClient();
        $client->request('GET', '/galleries/4');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $response, 'Should have id key');
        $this->assertArrayHasKey('name', $response, 'Should have name key');
        $this->assertArrayHasKey('images', $response, 'Should have images key');
        $this->assertCount(3, $response['images'], 'Should have 3 images in response');
    }

    /**
     * @covers \Sweet\GalleryBundle\Controller\GalleryController::postEditGalleryAction
     */
    public function testPostEditGallery()
    {
        $client = static::createClient();
        $this->requestPost($client, '/galleries/3', array(
            'name'  => 'TESTNAME'
        ));

        $gallery = $this->em->getRepository('SweetGalleryBundle:Gallery')
                ->find(3);

        $this->assertNotNull($gallery);
        $this->assertEquals('TESTNAME', $gallery->getName(), 'Gallery name should change');
    }

    /**
     * @covers \Sweet\GalleryBundle\Controller\GalleryController::postAddGalleryAction
     */
    public function testPostAddGallery()
    {
        $client = static::createClient();
        $this->requestPost($client, '/galleries', array(
            'name'  => 'NEW GALLERY'
        ));

        $gallery = $this->em->getRepository('SweetGalleryBundle:Gallery')
                ->find(6);

        $this->assertNotNull($gallery);
        $this->assertEquals('NEW GALLERY', $gallery->getName(), 'Should find newly created gallery in database');
    }

    /**
     * @covers \Sweet\GalleryBundle\Controller\GalleryController::deleteGalleryAction
     */
    public function testDeleteGalleryAction()
    {
        $client = static::createClient();
        $client->request('DELETE', '/galleries/3');

        $gallery = $this->em->getRepository('SweetGalleryBundle:Gallery')
                ->find(3);

        $this->assertNull($gallery);
    }
}
