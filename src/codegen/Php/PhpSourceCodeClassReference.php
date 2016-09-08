<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Codegen\SourceCodeClassReference;

class PhpSourceCodeClassReference extends SourceCodeClassReference
{
    const FULLNAME_PREFIX = '\\';
    const NAMESPACE_CLASSNAME_SEPARATOR = '\\';
    const NAMESPACE_NAMESPACE_SEPARATOR = '\\';

    public static $fileExtension = EXT_CLASS;

    public function __toString()
    {
        return $this->getSelfName();
    }

    public function getSelfName()
    {
        return $this->getStatic('class');
    }

    public function getFileName()
    {
        return $this->getName() . self::$fileExtension;
    }

    public function getFilePath()
    {
        $path = $this->getFileName();
        if ($this->getNamespaceParts()) {
            $path = implode(DIRECTORY_SEPARATOR, $this->getNamespaceParts()) . DIRECTORY_SEPARATOR . $path;
        }
        return $path;
    }

    public function getStatic($property)
    {
        return $this->getFullName() . '::' . $property;
    }

}