<?php

namespace Customers\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController {

    public $authservice = null;

    public function indexAction() {

        $form      = $this->getServiceLocator()->get('LoginForm');
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;
    }

    public function processAction() {

        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(NULL, array('controller' => 'index',
                        'action'     => 'index'));
        }

        $post = $this->request->getPost();
        $form = $this->getServiceLocator()->get('LoginForm');
        $form->setData($post);

        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('customers/login/index');
            return $model;
        }

        /**
         * проверка поступивших данных
         */
        $this->getAuthService()->getAdapter()
                ->setIdentity($post['login'])
                ->setCredential($post['pass']);

        $result = $this->getAuthService()->authenticate();

        if ($result->isValid()) {
            $customer = $this->getCustomerData($post['login']);

            $this->getAuthService()->getStorage()->write(
                    $customer);
            
            $this->redirect()->toRoute('info');
        } else {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('customers/login/index');
            return $model;
        }
    }

    public function savedbAction(array $data) {
        $customer       = new Customer();
        $customer->exchangeArray($data);
        $customersTable = $this->getServiceLocator()->get('CustomersTable');
        $customersTable->saveCustomer($customer);
        return true;
    }

    public function outAction() {
        $auth = $this->getServiceLocator()->get("Auth_Service");
        $auth->clearIdentity();
        $this->redirect()->toRoute('customers');
    }

    public function getAuthService() {
        if (is_null($this->authservice)) {

            $this->authservice = $this->getServiceLocator()->get('Auth_Service');
        }
        return $this->authservice;
    }

    /**
     * функция выбирает имя клиента по логину 
     * @param type $login
     * @return type
     */
    public function getCustomerData($login) {
        $customersTable = $this->getServiceLocator()->get('CustomersTable');
        $customer       = $customersTable->getUserByLogin($login);
        return $customer;
    }

}
