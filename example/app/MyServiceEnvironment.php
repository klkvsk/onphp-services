<?php
/**
 * Делаем окружение для всех наших сервисов
 * Этот класс создается вручную.
 */

use \OnPhp\Services\Base\IServiceEnvironment;
use \OnPhp\Services\Base\ServiceProvider;

class MyServiceEnvironment implements IServiceEnvironment {
	protected $userSession;
	protected $loggedUser;
	protected $language;

	/** @var ServiceProvider */
	public $serviceProvider;

	public static function assertIsInstance(IServiceEnvironment $environment = null) {
		Assert::isNotNull($environment, 'environment is required');
		Assert::isInstance(get_class($environment), MyServiceEnvironment::class, 'wrong type of environment provided');
	}

	public static function create(User $user = null, Language $language) {
		$self = new self;
		$self->loggedUser = $user;
		$self->language = $language;
		return $self;
	}

	public static function createFromHttpRequest(HttpRequest $httpRequest) {
		$self = new self;
		$self->userSession = UserSession::dao()->getByRequest($httpRequest);
		if ($self->userSession instanceof UserSession && $self->userSession->isAuthorized()) {
			$self->loggedUser = $self->userSession->getUser();
		}

		if ($httpRequest->hasHeaderVar('Accept')) {
			$self->language = Language::detect($httpRequest->getHeaderVar('Accept'));
		}
		if (!$self->language && $httpRequest->hasCookieVar('language')) {
			$self->language = Language::detect($httpRequest->getCookieVar('language'));
		}
		if (!$self->language) {
			$self->language = Language::createDefault();
		}

		return $self;
	}

	/** @return User */
	public function getLoggedUser() {
		return $this->loggedUser;
	}

	/** @return Language */
	public function getLanguage() {
		return $this->language;
	}
}