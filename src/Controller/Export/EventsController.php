<?php


namespace SALESmanago\Controller\Export;


use SALESmanago\Exception\SalesManagoException;

class EventsController
{
    public function exportContactExtEvents($data)
    {
        try {
            //$response = $this->service->exportContactExtEvents($this->settings, $data);
            //return json_encode($response);
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }


//    /**
//     * @throws SalesManagoException
//     * @var Settings $settings
//     * @param array $events
//     * @return array
//     */
//    public function exportContactExtEvents(Settings $settings, $events)
//    {
//        $data = array_merge($this->__getDefaultApiData($settings), array(
//            'events' => $events,
//        ));
//
//        $response = $this->request(self::METHOD_POST, self::METHOD_BATCH_ADD_EXT_EVENT, $data);
//        return $this->validateResponse($response);
//    }
}