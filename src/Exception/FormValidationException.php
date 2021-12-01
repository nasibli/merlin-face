<?php

namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FormValidationException extends \Exception
{
    /**
     * @var FormInterface
     */
    private $form;

    public function __construct(FormInterface $form)
    {
        parent::__construct('Form validation failed');
        $this->form = $form;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return FormErrorIterator
     */
    public function getErrors(): FormErrorIterator
    {
        return $this->form->getErrors(true);
    }
}
