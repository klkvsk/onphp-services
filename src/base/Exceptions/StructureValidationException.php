<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-24
 */

namespace OnPhp\Services\Base\Exceptions;


class StructureValidationException extends \Exception
{
    protected $form = null;

    public function __construct($message, \Form $form, \Exception $previous = null)
    {
        $this->form = $form;
        parent::__construct($message . ': ' . json_encode($form->getErrors()), 0, $previous);
    }

    public function getForm()
    {
        return $this->form;
    }
}