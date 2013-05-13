<?php

namespace Sweet\GalleryBundle\Controller;

use Imagine\Filter\Transformation;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sweet\GalleryBundle\Entity\Gallery;
use Sweet\GalleryBundle\Entity\Image;
use Sweet\GalleryBundle\Form\ImageType;
use Sweet\GalleryBundle\Response\JsonFormErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        /* @var $gallery Gallery */
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

            // @todo refactor
            $thumbnailWebPath = implode(DIRECTORY_SEPARATOR, array('uploads', 'thumbnails', md5($image->getFile()->getClientOriginalName() + time() + rand()).'.'.$image->getFile()->getClientOriginalExtension()));
            $thumbnailPath = implode(DIRECTORY_SEPARATOR, array($this->get('kernel')->getRootDir(), '..', 'web', $thumbnailWebPath));

            $imagine = new Imagine();
            $transformation = new Transformation();
            $transformation->thumbnail(new Box(300, 200), ImageInterface::THUMBNAIL_OUTBOUND)
                    ->save($thumbnailPath);
            $transformation->apply($imagine->open($image->getFile()));
            // end todo

            $image->setThumbnail($thumbnailWebPath);
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
