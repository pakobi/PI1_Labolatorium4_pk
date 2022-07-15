<?php

namespace Application\Controller;

use Application\Form\AutorForm;
use Application\Model\Autor;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AutorzyController extends AbstractActionController
{
    private Autor $autor;

    private AutorForm $autorForm;

    public function __construct(Autor $autor, AutorForm $autorForm)
    {
        $this->autor = $autor;
        $this->autorForm = $autorForm;
    }

    public function listaAction()
    {
        return new ViewModel([
            'autorzy' => $this->autor->pobierzWszystko(),
        ]);
    }

    public function dodajAction()
    {
        $this->autorForm->get('zapisz')->setValue('Dodaj');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->autorForm->setData($request->getPost());

            if ($this->autorForm->isValid()) {
                $this->autor->dodaj($request->getPost());

                return $this->redirect()->toRoute('autorzy');
            }
        }

        return new ViewModel(['tytul' => 'Dodawanie autora', 'form' => $this->autorForm]);
    }

    public function edytujAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('autorzy');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->autorForm->setData($request->getPost());

            if ($this->autorForm->isValid()) {
                $this->autor->aktualizuj($id, $request->getPost());

                return $this->redirect()->toRoute('autorzy');
            }
        } else {
            $daneKsiazki = $this->autor->pobierz($id);
            $this->autorForm->setData($daneKsiazki);
        }

        $viewModel = new ViewModel(['tytul' => 'Edytuj autora', 'form' => $this->autorForm]);
        $viewModel->setTemplate('application/autorzy/dodaj');

        return $viewModel;
    }

    public function informacjeAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('autorzy');
        }
        $daneKsiazki = $this->autor->pobierz($id);

        $viewModel = new ViewModel(['autor' => $daneKsiazki]);
        $viewModel->setTemplate('application/autorzy/informacje');

        return $viewModel;
    }

    public function usunAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('autorzy');
        }

        $this->autor->usun($id);

        return $this->redirect()->toRoute('autorzy');
    }
}