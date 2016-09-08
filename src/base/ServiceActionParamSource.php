<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-02
 */

namespace OnPhp\Services\Base;


class ServiceActionParamSource
{
    use AbstractEnumTrait;

    const FROM_QUERY   = 'query';
    const FROM_BODY    = 'body';
    const FROM_ROUTE   = 'route';
    const FROM_SESSION = 'session';
    const FROM_COOKIE  = 'cookie';
    const FROM_HEADER  = 'header';

    const _DEFAULT = self::FROM_QUERY;

    protected $id;

    public static function getConstList()
    {
        return [
            self::FROM_QUERY    => 'FROM_QUERY',
            self::FROM_BODY     => 'FROM_BODY',
            self::FROM_ROUTE    => 'FROM_ROUTE',
            self::FROM_SESSION  => 'FROM_SESSION',
            self::FROM_COOKIE   => 'FROM_COOKIE',
            self::FROM_HEADER   => 'FROM_HEADER',
        ];
    }

    public static function getFactoryNames()
    {
        return [
            self::FROM_QUERY    => 'fromQuery',
            self::FROM_BODY     => 'fromBody',
            self::FROM_ROUTE    => 'fromRoute',
            self::FROM_SESSION  => 'fromSession',
            self::FROM_COOKIE   => 'fromCookie',
            self::FROM_HEADER   => 'fromHeader',
        ];
    }

    protected function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFactoryName()
    {
        $names = self::getFactoryNames();
        return $names[$this->id];
    }

    public static function fromQuery  () { return self::create(self::FROM_QUERY);   }
    public static function fromBody   () { return self::create(self::FROM_BODY);    }
    public static function fromRoute  () { return self::create(self::FROM_ROUTE);   }
    public static function fromSession() { return self::create(self::FROM_SESSION); }
    public static function fromCookie () { return self::create(self::FROM_COOKIE);  }
    public static function fromHeader () { return self::create(self::FROM_HEADER);  }

}