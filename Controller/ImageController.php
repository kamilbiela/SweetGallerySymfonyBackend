<?php

namespace Sweet\GalleryBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sweet\GalleryBundle\Entity\Image;
use Sweet\GalleryBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;
use Sweet\GalleryBundle\Response\JsonFormErrorResponse;

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

    /**
     * @param int $id
     *
     * @Method({"GET"})
     * @Route("/images/{id}")
     * @return JsonResponse
     */
    public function getImageAction($id)
    {
        $image = $this->getDoctrine()->getRepository('SweetGalleryBundle:Image')
                ->find($id);

        if (!$image) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($image->toArray());
    }

    /**
     * @param Request $request
     * @param int     $galleryId
     * 
     * @Method({"POST"})
     * @Route("/galleries/{galleryId}/images")
     * @return JsonResponse
     */
    public function postAddImageAction(Request $request, $galleryId)
    {
        /* @var $gallery \Sweet\GalleryBundle\Entity\Gallery */
        $gallery = $this->getDoctrine()->getRepository('SweetGalleryBundle:Gallery')
                ->find($galleryId);

        if (!$gallery) {
            throw new NotFoundHttpException();
        }

        $image = new Image();
        $image->setGallery($gallery);

        $form = $this->createForm(new ImageType(), $image);
        $form->bind($request->request->all() + $request->files->all());

        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
            $uploadableManager->markEntityToUpload($image, $image->getFile());

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($image);
            $em->flush();

            return new Response(null, 201);
        } else {
            return new JsonFormErrorResponse($form);
        }

        return new JsonResponse(array('status' => 'error'), 400);
    }
}
