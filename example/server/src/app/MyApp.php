<?php

/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
class MyApp extends Singleton
{

    protected $serviceProvider;

    /** @return self */
    public static function me()
    {
        return self::getInstance(__CLASS__);
    }

    public function __construct()
    {
        $this->serviceProvider = new \OnPhp\Services\Base\ServiceProvider();
        $this->serviceProvider->setOwnLocation('back01.ap');
    }

    /** @return \OnPhp\Services\Base\ServiceProvider */
    public function getServiceProvider()
    {
        return $this->serviceProvider;
    }

    public function run()
    {
        // create runner
        $runner = \OnPhp\Services\Base\ServiceHttpRunner::create()
            ->addContainer(UsersContainer::me())
            ->addContainer(PostsContainer::me())
            ->setServiceProvider($this->getServiceProvider());

        // get request
        $httpRequest = \HttpRequest::create()
            ->setMethod(HttpMethod::get())
            ->setUrl(HttpUrl::create()->parse('http://ap.ru/users/list'))
            ->setServerVar('REQUEST_URI', 'http://ap.ru/users/list');

        // detect user/session/language/etc.
        $env = MyServiceEnvironment::createFromHttpRequest($httpRequest);

        // route request and run service action
        $httpResponse = $runner->run($httpRequest, $env);

        // output response
        header('Content-Type: application/json');
        echo $httpResponse;
    }
}
