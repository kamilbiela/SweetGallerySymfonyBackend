<?php

namespace Sweet\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for handling image requests
 *
 */
class ImageController extends Controller
{
    /**
     * @param int $galleryId
     *
     * @Method({"GET"})
     * @Route("/galleries/{galleryId}/images")
     * @return JsonResponse
     */
    public function getGalleryImagesAction($galleryId)
    {
        $images = $this->getDoctrine()->getRepository('SweetGalleryBundle:Image')
                ->findAllInGallery($galleryId);

        $data = array();
        foreach ($images as $image) {
            $data[] = $image->toArray();
        }

        return new JsonResponse($data);
    }
}
