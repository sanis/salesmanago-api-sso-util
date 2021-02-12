<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Response;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\Collections\Collection;
use SALESmanago\Model\Collections\ContactsCollection;
use SALESmanago\Model\Collections\EventsCollection;
use SALESmanago\Model\ConfModel;
use SALESmanago\Model\ContactModel;
use function PHPUnit\Framework\throwException;

class ExportService
{
    const
        REQUEST_METHOD_POST = 'POST',
        API_METHOD_EXPORT_EVENTS = '/api/contact/batchAddContactExtEvent',
        API_METHOD_EXPORT_CONTACTS = '/api/contact/batchupsert';

    private $RequestService;
    private $conf;
    private $ConfModel;

    public function __construct(Configuration $conf)
    {
        $this->conf = $conf;
        $this->ConfModel = new ConfModel($conf);
        $this->RequestService = new RequestService($conf);
    }

    /**
     * @param Collection $collection
     * @return Response
     * @throws Exception
     */
    public function export(Collection $collection)
    {
        switch(get_class($collection)) {
            case ContactsCollection::class:
                $endpoint = self::API_METHOD_EXPORT_CONTACTS;
                break;
            case EventsCollection::class:
                $endpoint = self::API_METHOD_EXPORT_EVENTS;
                break;
            default:
                throw new Exception(get_class($collection) . 'can\'t be exported');
        }

        $settings = $this->ConfModel->getAuthorizationApiDataWithOwner();
        $collectionArray = $collection->toArray();

        $data = array_merge($settings, $collectionArray);

        return $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            $endpoint,
            $data
        );
    }

}