<?php

namespace Customers\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct($name = null) {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->add(array(
            'name'       => 'login',
            'attributes' => array(
                'class'       => 'form-control',
                'type'        => 'text',
                'required'    => 'required',
                'placeholder' => 'Login'
            ),
            'options'    => array(
                'label' => 'Login'),
            'filters'    => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'pass',
            'attributes' => array(
                'class'       => 'form-control',
                'type'        => 'password',
                'required'    => 'required',
                'placeholder' => 'Password'
            ),
            'options'    => array(
                'label' => 'Password'),
        ));

        $this->add(array(
            'name'       => 'submit',
            'attributes' => array(
                'class' => 'btn btn-primary',
                'type'  => 'submit',
            ),
            'options'    => array(
                'label' => 'Login',
            ),
        ));
    }

}
