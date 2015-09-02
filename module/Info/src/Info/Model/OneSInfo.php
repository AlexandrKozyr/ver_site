<?php

namespace Info\Model;

class OneSInfo {

    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    /**
     * using for pulling data about clients from 1C
     * 
     * @return array for aving at database
     */
    public function getListOfSuppliers() {
        return $this->client->GetListOfSuppliers();
    }

    /**
     * using for geting a list of contracts for current client
     * @param int $id_1c - 1C client's identificator
     * @return array of all contracts for current client
     */
    public function getListOfContracts($id_1c) {

        return $this->client->GetListOfContracts(array(
                    'IDSuppliers' => (string) $id_1c));
    }

    /**
     * method send query to 1C for getting a trade liability between mobilluck
     * and client
     * 
     * @param int $id_1c - 1C client's identificator
     * @param string $startDate - date for start of period for query to 1C side 'yyyy-mm-dd'
     * @param string $endDate - date for end of period for query to 1C side 'yyyy-mm-dd'
     * @param int $contractId - contract identificator, if not set we send empty string
     * @return array of trade liability data or boolean false if not permised for current user
     */
    public function getTradeLiability($id_1c, $startDate, $endDate, $contractId = "") {

        return $this->client->GetTradeLiability(array(
                    'IDSuppliers' => (string) $id_1c,
                    'IDContract'  => (string) $contractId,
                    'DateBegin'   => (string) $startDate,
                    'DataEnd'     => (string) $endDate));
    }

    /**
     * method checks can or not some user look at TradeLiability tree
     * 
     * @param int $id_1c - 1C client's identificator
     * @return string 1 or 0 depends of can or cannot to get TradeLiability tree
     */
    public function checkAvailabilityOfReconciliation($id_1c) {
        return $this->client->CheckAvailabilityReconciliation(array(
                    'IDSuppliers' => (string) $id_1c));
    }

    /**
     * method send query to 1C base and returns an array of products receipts for current client
     * 
     * @param int $id_1c - 1C client's identificator
     * @param string $startDate - date for start of period for query to 1C side 'yyyy-mm-dd'
     * @param string $endDate - date for end of period for query to 1C side 'yyyy-mm-dd'
     * @param int $contractId - contract identificator, if not set we send empty string
     * @return array receipts of products for current contract (if $contractId is set) or for all contracts for it
     * client(if $contractId isn't set)
     */
    public function getReceiptsOfProducts($id_1c, $startDate, $endDate, $contractId = "") {
        return $this->client->GetReceiptsOfProducts(array(
                    'IDSuppliers' => (string) $id_1c,
                    'IDContract'  => (string) $contractId,
                    'DateBegin'   => (string) $startDate,
                    'DataEnd'     => (string) $endDate));
    }

    /**
     * method send query to 1C base and returns an array of products returns for current client
     * 
     * @param int $id_1c - 1C client's identificator
     * @param string $startDate - date for start of period for query to 1C side 'yyyy-mm-dd'
     * @param string $endDate - date for end of period for query to 1C side 'yyyy-mm-dd'
     * @param int $contractId - contract identificator, if not set we send empty string
     * @return array returns of products for current contract (if $contractId is set) or for all contracts for it
     * client(if $contractId isn't set)
     */
    public function getReturnsOfProducts($id_1c, $startDate, $endDate, $contractId = "") {
        return $this->client->GetReturnsOfProducts(array(
                    'IDSuppliers' => (string) $id_1c,
                    'IDContract'  => (string) $contractId,
                    'DateBegin'   => (string) $startDate,
                    'DataEnd'     => (string) $endDate));
    }

    /**
     * 
     * @param int $id_1c - 1C client's identificator
     * @param int $documentId - current document identificator
     * @return array returns of products for current document
     */
    public function getProductsFromDocument($id_1c, $documentId) {
        return $this->client->GetProductsFromDocument(array(
                    'IDSuppliers' => (string) $id_1c,
                    'IDDocument'  => (string) stripslashes($documentId)));
    }

}
