<?php

namespace Sweet\GalleryBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;

/**
 * JsonResponse that accepts form as $data argument
 */
class JsonFormErrorResponse extends JsonResponse
{
    /**
     * {@inheritdoc}
     */
    public function __construct($data = null, $status = 400, $headers = array())
    {
        if ($data instanceof Form) {
            $form = $data;
            $errors = (array) $this->getFormErrors($form);
        }

        parent::__construct(array(
            'status' => 'error',
            'errors' => $errors
        ), $status, $headers);
    }

    /**
     * @param Form $form
     *
     * @return array
     */
    public function getFormErrors($form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $fieldName => $childForm) {
            if (count($childForm->getErrors())) {
                $errors[$fieldName] = $this->getFormErrors($childForm);
            }
        }

        return $errors;
    }
}