<?php

namespace Customers\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Customers\Model\SOAPSave;
use Zend\View\Model\ViewModel;

class SOAPController extends AbstractActionController {

    public function soapAction() {
        //$view = new ViewModel();
        //$view->setTerminal(true);
        if (isset($_GET['wsdl'])) {
            $autodiscover = new AutoDiscover();
            $autodiscover->setClass('Customers\Model\SOAPSave')
                    ->setUri('http://zf2_soap.loc/customers/SOAP/soap');

            
            $viewModel      = new ViewModel(array(
                'ad' => $autodiscover,
            ));
            $viewModel->setTerminal(true);
            return $viewModel;
        } else {
            // pointing to the current file here
            $soap = new Server("http://zf2_soap.loc/customers/SOAP/soap?wsdl");
            $soap->setClass('Customers\Model\SOAPSave');
            $soap->handle();
            $viewModel->setTemplate('customers/SOAP/soap_1');
            $viewModel->setTerminal(true);
            return $viewModel;
        }

        //return $view;
    }

}
