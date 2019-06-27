<?php
namespace User\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class UserFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$userMapper = $container->get(\User\Mapper\UserProfile::class);
		return new $userMapper;
	}
}
