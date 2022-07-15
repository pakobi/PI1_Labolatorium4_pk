<?php

namespace Application\Controller;

use Application\Form\AutorForm;
use Application\Model\Autor;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AutorzyControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $ksiazka = $container->get(Autor::class);
        $ksiazkaForm = $container->get(AutorForm::class);

        return new AutorzyController($ksiazka, $ksiazkaForm);
    }
}