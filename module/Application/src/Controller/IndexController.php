<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Model\Data;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function __construct(private Data $data)
    {
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function dataAction()
    {
        return ['dzisiaj' => $this->data->dzisiaj()];
//        return new ViewModel(['dzisiaj' => $model->dzisiaj()]);
    }

    public function dniTygodniaAction()
    {
        return ['dni_tygodnia' => $this->data->dniTygodnia()];
    }
}
