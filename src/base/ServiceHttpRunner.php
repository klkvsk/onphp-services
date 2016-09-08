<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */

namespace OnPhp\Services\Base;

use \Assert;
use \HttpRequest;
use \ForbiddenException;
use \RouterException;
use \RouterTransparentRule;
use \RouterStaticRule;

class ServiceHttpRunner
{
    const ROUTER_BASE_URL = '/';

    /** @var IServiceContainer[] */
    protected $containers = [];
    /** @var IServiceAccessChecker */
    protected $accessChecker;
    /** @var IServiceProvider */
    protected $serviceProvider;

    /** @return static */
    public static function create()
    {
        return new static;
    }

    /**
     * @return IServiceAccessChecker
     */
    public function getAccessChecker()
    {
        return $this->accessChecker;
    }

    /**
     * @param IServiceAccessChecker $accessChecker
     * @return $this
     */
    public function setAccessChecker(IServiceAccessChecker $accessChecker)
    {
        $this->accessChecker = $accessChecker;
        return $this;
    }

    /**
     * @return IServiceProvider
     */
    public function getServiceProvider()
    {
        return $this->serviceProvider;
    }

    /**
     * @param IServiceProvider $serviceProvider
     * @return $this
     */
    public function setServiceProvider(IServiceProvider $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
        return $this;
    }

    /**
     * @param IServiceContainer $container
     * @return $this
     */
    public function addContainer(IServiceContainer $container)
    {
        $this->containers[] = $container;
        return $this;
    }

    /**
     * @param HttpRequest $httpRequest
     * @param IServiceEnvironment|null $environment
     * @return string
     * @throws ForbiddenException
     * @throws RouterException
     * @throws \Exception
     * @throws \WrongArgumentException
     * @throws \WrongStateException
     */
    public function run(HttpRequest $httpRequest, IServiceEnvironment $environment = null)
    {
        $requestUri = $httpRequest->getUrl();
        $path = $requestUri->getPath();
        if (strpos($path, self::ROUTER_BASE_URL) !== 0) {
            // not our request
            return null;
        }

        // clean base path
        $path = substr($path, strlen(self::ROUTER_BASE_URL));
        // remove doubled slashes
        $path = preg_replace('/\/+/', '/', $path);

        list($servicePath, $actionPath) = explode('/', $path, 2);

        $protoService = null;
        foreach ($this->containers as $container) {
            $service = $container->getProtoServiceByHttpPath($servicePath);
            if ($service instanceof ProtoService) {
                $protoService = $service;
                break;
            }
        }

        if (!$protoService) {
            throw new RouterException(
                'could not find service for path "' . self::ROUTER_BASE_URL . $servicePath . '"'
            );
        }

        $protoAction = null;
        $routeParams = [];
        foreach ($protoService->getActions() as $action) {
            if ($action->getHttpMethod()->getId() != $httpRequest->getMethod()->getId()) {
                continue;
            }
            $routeProtoParams = $action->getParamsFrom(ServiceActionParamSource::fromRoute());
            $routePath = self::ROUTER_BASE_URL . $protoService->getHttpPath() . '/' . $action->getHttpPath();

            if (count($routeProtoParams) > 0) {
                $routingRule = RouterTransparentRule::create($routePath);
                $routeResult = $routingRule->match($httpRequest);
                if (is_array($routeResult) && count($routeResult) > 0) {
                    $routeParams = $routeResult;
                }
            } else {
                $routingRule = RouterStaticRule::create($routePath);
                $routeResult = $routingRule->match($httpRequest);
                if ($routeResult !== false) {
                    $protoAction = $action;
                    $routeParams = [];
                }
            }
        }

        if (!$protoAction) {
            throw new RouterException(
                'could not find service action for path "' . self::ROUTER_BASE_URL . $servicePath . '/' . $actionPath . '"'
            );
        }

        if ($this->getAccessChecker() && !$this->getAccessChecker()->isActionAllowed($protoAction, $environment)) {
            throw new ForbiddenException;
        }

        $params = [
            ServiceActionParamSource::FROM_ROUTE => $routeParams,
            ServiceActionParamSource::FROM_QUERY => $httpRequest->getGet(),
            ServiceActionParamSource::FROM_BODY => $httpRequest->getPost(),
            ServiceActionParamSource::FROM_SESSION => $httpRequest->getSession(),
            ServiceActionParamSource::FROM_COOKIE => $httpRequest->getCookie(),
            ServiceActionParamSource::FROM_HEADER => $httpRequest->getHeaderList(),
        ];

        $protoParams = $protoAction->getParams();
        $methodInput = [];
        $form = \Form::create();
        foreach ($protoParams as $param) {
            Assert::isIndexExists($params, $param->getFrom()->getId(), 'unsupported "from" param attribute');

            $paramValue = null;
            if (array_key_exists($param->getName(), $params[$param->getFrom()->getId()])) {
                $paramValue = $params[$param->getFrom()->getId()][$param->getName()];
            }

            $methodInput[$param->getName()] = $paramValue;
            $form->add($param->getPrimitive());
        }

        $form->clean();
        $form->import($methodInput);

        if ($form->getErrors()) {
            // todo: reply with http 400 response
            throw new \WrongStateException(json_encode($form->getErrors()));
        }

        $methodArgs = [];
        foreach ($protoParams as $param) {
            $methodArgs[$param->getName()] = $form->getValue($param->getName());
        }

        $service = $this->getServiceProvider()->getInstance($protoService, $environment);

        $result = call_user_func_array([$service, $protoAction->getName()], $methodInput);

        $protoAction->getReturn()->verify($result);

        return json_encode($result, JSON_PRETTY_PRINT);
    }


}