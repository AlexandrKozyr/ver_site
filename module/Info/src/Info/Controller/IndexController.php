<?php

namespace Info\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Info\Model\OneSInfo;
use Customers\Model\Customer;

class IndexController extends AbstractActionController {

    protected $id_1c;

    public function indexAction() {

        $auth        = $this->getServiceLocator()->get("Auth_Service");
        $customer    = $auth->getStorage()->read();
        $this->id_1c = $customer->id_1c;


        try {
            $soapClient  = $this->getServiceLocator()->get('SoapClient');
            $oneS        = new OneSInfo($soapClient);
//            сохранить данные с 1с о пользователях
//        $supp = $oneS->getListOfSuppliers()->return;
//        $this->saveDBFrom1C($supp->Row);
            $sverka      = $oneS->checkAvailabilityOfReconciliation($customer->id_1c)->return;
            $listofcontr = $this->makeArray($oneS->getListOfContracts($customer->id_1c)->return->Row);
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
        $view = new ViewModel(array(
            'sverka'      => $sverka,
            'listofcontr' => $listofcontr));
        return $view;
    }

    public function tradeAction() {
        $customer    = $this->getServiceLocator()->get("CurrentCustomer");
        $this->id_1c = $customer->id_1c;

        $start     = $this->getRequest()->getPost('DateBegin');
        $end       = $this->getRequest()->getPost('DataEnd');
        $conractId = $this->getRequest()->getPost('ContractId');
        try {
            $soapClient = $this->getServiceLocator()->get('SoapClient');
            $oneS       = new OneSInfo($soapClient);
            $result     = $oneS->getTradeLiability($this->id_1c, $start, $end, $conractId);

            if (isset($result->return->Row)) {
                $trade = $this->makeArrayContracts($result->return->Row);
                $view  = new ViewModel(array(
                    'trade' => $trade,));
                $view->setTemplate('info/index/trade');
                $view->setTerminal(true);
                return $view;
            } else {
                $view = new ViewModel();
                $view->setTemplate('info/index/noresult');
                $view->setTerminal(true);
                return $view;
            }
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function receiptsAction() {
        $customer    = $this->getServiceLocator()->get("CurrentCustomer");
        $this->id_1c = $customer->id_1c;

        $start     = $this->getRequest()->getPost('DateBegin');
        $end       = $this->getRequest()->getPost('DataEnd');
        $conractId = $this->getRequest()->getPost('ContractId');
        try {
            $soapClient = $this->getServiceLocator()->get('SoapClient');
            $oneS       = new OneSInfo($soapClient);
            $result     = $oneS->getReceiptsOfProducts($this->id_1c, $start, $end, $conractId);
            if (isset($result->return->Row)) {
                $receipts = $this->makeArrayContractsRR($result->return->Row);
                $view     = new ViewModel(array(
                    'receipts' => $receipts,));
                $view->setTemplate('info/index/receipts');
                $view->setTerminal(true);
                return $view;
            } else {
                $view = new ViewModel();
                $view->setTemplate('info/index/noresult');
                $view->setTerminal(true);
                return $view;
            }
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function returnsAction() {
        $customer    = $this->getServiceLocator()->get("CurrentCustomer");
        $this->id_1c = $customer->id_1c;

        $start     = $this->getRequest()->getPost('DateBegin');
        $end       = $this->getRequest()->getPost('DataEnd');
        $conractId = $this->getRequest()->getPost('ContractId');
        try {
            $soapClient = $this->getServiceLocator()->get('SoapClient');
            $oneS       = new OneSInfo($soapClient);
            $result     = $oneS->getReturnsOfProducts($this->id_1c, $start, $end, $conractId);


            if (isset($result->return->Row)) {
                $returns = $this->makeArrayContractsRR($result->return->Row);
                $view     = new ViewModel(array(
                    'returns' => $returns,));
                $view->setTemplate('info/index/returns');
                $view->setTerminal(true);
                return $view;
            } else {
                $view = new ViewModel();
                $view->setTemplate('info/index/noresult');
                $view->setTerminal(true);
                return $view;
            }
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function productsAction() {
        $customer    = $this->getServiceLocator()->get("CurrentCustomer");
        $this->id_1c = $customer->id_1c;

        $documentId = $this->getRequest()->getPost('DocumentID');
        $debit      = $this->getRequest()->getPost('Debit');
        $declar     = $this->getRequest()->getPost('Declar');

        try {
            $soapClient = $this->getServiceLocator()->get('SoapClient');
            $oneS       = new OneSInfo($soapClient);
            $result     = $oneS->getProductsFromDocument($this->id_1c, $documentId);

            $products = $this->makeArray($result->return->Row);
            $view     = new ViewModel(array(
                'products' => $products,
                'declar'   => $declar,
                'debit'    => $debit,));
            $view->setTemplate('info/index/products');
            $view->setTerminal(true);
            return $view;
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * method parse soap information and saves to DB
     * @param array $data
     */
    private function saveDBFrom1C(array $data) {
        $newData = array();
        for ($i = 0; $i < count($data); $i++) {
            $newData[$i]['id_1c']     = $data[$i]->ID;
            $newData[$i]['pass']      = $data[$i]->Password;
            $newData[$i]['login']     = $data[$i]->Login;
            $newData[$i]['email']     = $data[$i]->Email;
            $newData[$i]['name']      = $data[$i]->Name;
            $newData[$i]['is_active'] = strlen($data[$i]->Login) > 0 ? 1 : 0;
        }

        foreach ($newData as $item) {
            $this->saveCustomerToDB($item);
        }
    }

    /**
     * used to save an info about customer to DB
     * @param array $data
     * @return boolean
     */
    private function saveCustomerToDB(array $data) {
        $customer       = new Customer();
        $customer->exchangeArray($data);
        $customersTable = $this->getServiceLocator()->get('CustomersTable');
        $customersTable->saveCustomer($customer);
        return true;
    }

    /**
     * method checks is $result an object and if is not makes an aray with it
     * @param object/array $result
     * @return array $result
     */
    private function makeArray($result) {
        if (is_object($result)) {
            $temp   = array();
            $temp[] = $result;
            $result = $temp;
        }

        return $result;
    }

    /**
     * method checks is $result an object and if is not makes an aray with it
     * @param object/array $result
     * @return array $result
     */
    private function makeArrayContracts($result) {
        if (is_object($result)) {
            $temp   = array();
            $temp[] = $result;
            $result = $temp;
        }

        $result = $this->makeArrayDate($result);
        $result = $this->makeArrayDoc($result);

        return $result;
    }

    /**
     * method checks is $result an object and if is not makes an aray with it
     * @param array $result
     * @return array $result
     */
    private function makeArrayDate($result) {
        foreach ($result as &$item) {
            if (is_object($item->Rows->Row)) {
                $temp            = array();
                $temp[]          = $item->Rows->Row;
                $item->Rows->Row = $temp;
            }
        }
        return $result;
    }

    /**
     * method checks is $result an object and if is not makes an aray with it
     * @param array $result
     * @return array $result
     */
    private function makeArrayDoc($result) {
        foreach ($result as &$item) {
            foreach ($item->Rows->Row as &$value) {
                if (is_object($value->Rows->Row)) {
                    $temp             = array();
                    $temp[]           = $value->Rows->Row;
                    $value->Rows->Row = $temp;
                }
            }
        }
        return $result;
    }

    /**
     * method checks is $result an object and if is not makes an array with it for returns and receipts
     * @param object/array $result
     * @return array $result
     */
    private function makeArrayContractsRR($result) {
        if (is_object($result)) {
            $temp   = array();
            $temp[] = $result;
            $result = $temp;
        }

        $result = $this->makeArrayDateRR($result);
        $result = $this->makeArrayDocRR($result);

        return $result;
    }

    /**
     * method checks is $result an object and if is not makes an array with it for returns and receipts
     * @param array $result
     * @return array $result
     */
    private function makeArrayDateRR($result) {
        foreach ($result as &$item) {
            if (is_object($item->Row)) {
                $temp      = array();
                $temp[]    = $item->Row;
                $item->Row = $temp;
            }
        }
        return $result;
    }

    /**
     * method checks is $result an object and if is not makes an array with it for returns and 
     * @param array $result
     * @return array $result
     */
    private function makeArrayDocRR($result) {
        foreach ($result as &$item) {
            foreach ($item->Row as &$value) {
                if (is_object($value->Row)) {
                    $temp       = array();
                    $temp[]     = $value->Row;
                    $value->Row = $temp;
                }
            }
        }
        return $result;
    }

}
