<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\LoyaltyProgram;
use SALESmanago\Entity\Response;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\LoyaltyProgramService;

class LoyaltyProgramController
{
    protected $conf;
    protected $service;

    /**
     * LoyaltyProgramController constructor.
     *
     * @param ConfigurationInterface $conf
     * @throws Exception
     */
    public function __construct(ConfigurationInterface $conf)
    {
        Configuration::setInstance($conf);
        $this->conf    = $conf;
        $this->service = new LoyaltyProgramService($this->conf);
    }

    /**
     * @param LoyaltyProgram $LoyaltyProgram
     * @return Response
     * @throws Exception
     */
    public function add(LoyaltyProgram $LoyaltyProgram)
    {
        $Response = $this->service->add($LoyaltyProgram);
        // @todo with Response
        return $Response;
    }

    /**
     * @param LoyaltyProgram $LoyaltyProgram
     * @return Response
     * @throws Exception
     */
    public function remove(LoyaltyProgram $LoyaltyProgram)
    {
        $Response = $this->service->remove($LoyaltyProgram);
        // @todo with Response
        return $Response;
    }

    /**
     * @param LoyaltyProgram $LoyaltyProgram
     * @return Response
     * @throws Exception
     */
    public function modifyPoints(LoyaltyProgram $LoyaltyProgram)
    {
        $Response = $this->service->modifyPoints($LoyaltyProgram);
        // @todo with Response
        return $Response;
    }
}