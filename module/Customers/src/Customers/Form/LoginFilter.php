<?php

namespace Customers\Form;
use Zend\InputFilter\InputFilter;
class LoginFilter extends InputFilter {

    public function __construct() {

        $this->add(array(
            'name'       => 'login',
            'required'   => true,
            'filters'    => array(
                array(
                    'name' => 'StripTags',
                ),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 4,
                        'max'      => 12,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'     => 'pass',
            'required' => true,
        ));
    }

}
