<?php

declare(strict_types=1);

namespace Application;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';

        return $config;
    }

    public function getServiceConfig(): array
    {
        return [
            'initializers' => [
                'db' => function (ContainerInterface $container, $instance) {
                    if ($instance instanceof AdapterAwareInterface) {
                        $instance->setDbAdapter($container->get(AdapterInterface::class));
                    }
                },
            ],
        ];
    }
}
