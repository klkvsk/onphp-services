<?php
/**
 * Делаем базу для всех наших сервисов
 * Этот класс создается вручную.
 */
use \OnPhp\Services\Base\IService;
use \OnPhp\Services\Base\IServiceEnvironment;
use \OnPhp\Services\Base\IServiceContainer;

abstract class MyBaseService implements IService {
	/** @var MyServiceEnvironment */
	protected $env;

	public function __construct(IServiceEnvironment $environment = null) {
		MyServiceEnvironment::assertIsInstance($environment);
		$this->env = $environment;
	}

	/** @return \OnPhp\Services\Base\IServiceProvider */
	public function getServiceProvider() {
		return MyApp::me()->getServiceProvider();
	}

	/**
	 * @param string $serviceName
	 * @return \OnPhp\Services\Base\IService
	 */
	public function resolveService(IServiceContainer $container, $serviceName) {
		return $this->getServiceProvider()->getInstance($container->getProtoServiceByName($serviceName), $this->env);
	}

}