<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-24
 */

namespace OnPhp\Services\Meta\Exceptions;

use Exception;

class MetaConfigurationException extends \Exception
{
    protected $xmlNode;

    public function __construct($message, \SimpleXMLElement $xmlNode, Exception $previous = null)
    {
        $this->xmlNode = $xmlNode;
        parent::__construct($message, 0, $previous);
    }

}