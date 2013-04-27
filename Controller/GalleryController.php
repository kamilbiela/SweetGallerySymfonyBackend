<?php

namespace Sweet\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sweet\GalleryBundle\Entity\Gallery;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for handling galleries requests
 *
 * @Route("/galleries")
 */
class GalleryController extends Controller
{
    /**
     * @Route("")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getIndexAction()
    {
        $galleries = $this->getDoctrine()
                ->getRepository('SweetGalleryBundle:Gallery')
                ->findAll();

        $data = array();

        foreach ($galleries as $gallery) {
            $data[] = $gallery->toArrayList();
        }

        return new JsonResponse($data);
    }

    /**
     * @param int $id
     * 
     * @Route("/{id}")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getGalleryAction($id)
    {
        $gallery = $this->getDoctrine()
                ->getRepository('SweetGalleryBundle:Gallery')
                ->find($id);

        if (!$gallery) {
            return new NotFoundHttpException();
        }

        return new JsonResponse($gallery->toArray());
    }

    /**
     * @param int $id
     * 
     * @Route("/{id}")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function postEditGalleryAction($id)
    {
        /* @var $gallery \Sweet\GalleryBundle\Entity\Gallery */
        $gallery = $this->getDoctrine()
                ->getRepository('SweetGalleryBundle:Gallery')
                ->find($id);

        if (!$gallery) {
            return new NotFoundHttpException();
        }

        $data = json_decode($this->getRequest()->getContent(), true);

        $gallery->setName($data['name']);

        $this->getDoctrine()->getManager()->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("")
     * @Method({"POST"})
     * @return JsonSuccessResponse
     */
    public function postAddGalleryAction()
    {
        $data = json_decode($this->getRequest()->getContent(), true);

        $gallery = new Gallery();
        $gallery->setName($data['name']);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($gallery);
        $em->flush();

        return new Response(null, 201);
    }

    /**
     * @param int $id
     * 
     * @Route("/{id}")
     * @Method({"DELETE"})
     * @return JsonSuccessResponse
     */
    public function deleteGalleryAction($id)
    {
        $gallery = $this->getDoctrine()
                ->getRepository('SweetGalleryBundle:Gallery')
                ->find($id);

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($gallery);
        $em->flush();

        return new Response(null, 204);
    }
}
