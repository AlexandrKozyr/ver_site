<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Customers\Model\Customer;
use Customers\Model\CustomersTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DBTableAuthAdapter;
use Zend\Soap\Client;

class Module {

    

    public function onBootstrap(MvcEvent $e) {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_ROUTE, function($e) {
            $auth = $e->getApplication()->getServiceManager()->get("Auth_Service");

            $application         = $e->getApplication();
            $viewModel           = $application->getMvcEvent()->getViewModel();
            $viewModel->UserAuth = false;
            if ($auth->hasIdentity()) {
                $viewModel->UserAuth = true;
                $viewModel->UserName = $auth->getStorage()->read()->name;
            }

            $routeMatch = $e->getRouteMatch();
            $controller = $routeMatch->getParam('controller');
            $whitelist  = array(
                'Customers\Controller\Login'
            );
            if (!in_array($controller, $whitelist)) {
                if (!$auth->hasIdentity()) {
                    $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
                        $controller = $e->getTarget();
                        $controller->plugin('redirect')->toRoute('customers');
                    }, 100);
                }
            }
        });
//        $config  = new StandardConfig();
//        $config->setOptions(array(
//            'remember_me_seconds' => 10,
//            'name'                => 'zf2',
//        ));
//        $manager = new SessionManager($config);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'abstruct_factories'    => array(),
            'aliases'               => array(),
            'factories'             => array(
                //менеджер служб - база данных

                'CustomersTable' => function($sm) {
                    $tableGateway = $sm->get('CustomersTableGateway');
                    $table        = new CustomersTable($tableGateway);
                    return $table;
                },
                'CustomersTableGateway' => function($sm) {
                    $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Customer);
                    return new TableGateway('ver_customers', $dbAdapter, null, $resultSetPrototype);
                },
                //менеджер служб - форма авторизации      
                'loginForm' => function($sm) {
                    $form = new \Customers\Form\LoginForm();
                    $form->setInputFilter($sm->get('LoginFilter'));
                    return $form;
                },
                // фильтр для формы авторизации
                'loginFilter' => function() {
                    return new \Customers\Form\LoginFilter();
                },
                //служба аутификации
                'Auth_Service' => function($sm) {
                    $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter = new DBTableAuthAdapter($dbAdapter, 'ver_customers', 'login', 'pass');
                    $authService        = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    return $authService;
                },
                //служба клиента

                'SoapClient2' => function() {
                    $wsdl       = "http://217.12.219.215/ERVGroup/ws/SiteOfReconciliationTradeLiability.1cws?wsdl";
                    $soapClient = new Client($wsdl, array('login'    => 'web',
                        'password' => 'web1231980'));
                    return $soapClient;
                },
                'SoapClient' => function() {
                    $wsdl       = "http://192.168.0.229/Mobilluck/ws/SiteOfReconciliationTradeLiability.1cws?wsdl";
                    $soapClient = new Client($wsdl, array('login'    => 'site',
                        'password' => 'site'));
                    return $soapClient;
                },
                //объект текущего пользователя
                'CurrentCustomer' => function($sm) {
                    $auth     = $sm->get("Auth_Service");
                    $customer = $auth->getStorage()->read();
                    return $customer;
                }
                    ),
                    'invokables' => array(),
                    'services'   => array(),
                    'shared'     => array(),
                );
            }

        }
        