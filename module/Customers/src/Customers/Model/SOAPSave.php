<?php

namespace Customers\Model;

class SOAPSave {

    /**
     * method parse soap information and saves to DB
     * @param string $data
     * @return string 
     */
    public function saveDB( $data) {
        
//        $newData = array();
//        for ($i = 0; $i < count($data); $i++) {
//            $newData[$i]['id_1c']     = $data[$i]->ID;
//            $newData[$i]['pass']      = $data[$i]->Password;
//            $newData[$i]['login']     = $data[$i]->Login;
//            $newData[$i]['email']     = $data[$i]->Email;
//            $newData[$i]['name']      = $data[$i]->Name;
//            $newData[$i]['is_active'] = strlen($data[$i]->Login) > 0 ? 1 : 0;
//        }
//
//        foreach ($newData as $item) {
//            $this->saveCustomerToDB($item);
//        }
        return $data.'121312';
    }

}
