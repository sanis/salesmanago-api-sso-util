<?php


namespace SALESmanago\Controller\Export;


use SALESmanago\Exception\SalesManagoException;

class ContactsController
{
    public function exportContacts($data)
    {
        try {
            //$response = $this->service->exportContacts($this->settings, $data);
            //return json_encode($response);
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }


//    /**
//     * @throws SalesManagoException
//     * @var Settings $settings
//     * @param array $upsertDetails
//     * @return array
//     */
//    public function exportContacts(Settings $settings, $upsertDetails)
//    {
//        $data = array_merge($this->__getDefaultApiData($settings), array(
//            'upsertDetails' => $upsertDetails,
//        ));
//
//        $data = array_merge($data, ['useApiDoubleOptIn' => false]);
//
//        $response = $this->request(self::METHOD_POST, self::METHOD_BATCH_UPSERT, $data);
//        return $this->validateResponse($response);
//    }
}