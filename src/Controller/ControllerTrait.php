<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Response;


trait ControllerTrait
{
    /**
     * @return Response
     */
    protected function buildResponse()
    {
        return new Response();
    }

    protected function changeEndpointForEs($endpoint)
    {
        return str_replace('.pl', '.es', $endpoint);
    }
}
