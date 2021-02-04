<?php

namespace SALESmanago\Services;

use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Response;
use SALESmanago\Entity\User;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\UserModel;
use SALESmanago\Model\ConfModel;

/**
 * Class UserAccountService - implements functionality with salesmanago user/owner account;
 * @package SALESmanago\Services
 */
class UserAccountService
{
    const
        REQUEST_METHOD_POST = 'POST',
        METHOD_LOGIN_AUTHORIZE = '/api/authorization/token',
        METHOD_ACCOUNT_INTEGRATION = '/api/account/integration',
        METHOD_LIST_USERS = '/api/user/listByClient',
        METHOD_REDIRECT_TO_APP = '/api/authorization/authorize?t=',
        METHOD_REFRESH_TOKEN = '/api/authorization/refreshToken';

    /**
     * @var Configuration - integration configuration
     */
    protected $conf;

    /**
     * @var UserModel
     */
    protected $UserModel;

    /**
     * @var ConfModel
     */
    protected $ConfModel;

    /**
     * @var RequestService
     */
    protected $RequestService;

    public function __construct(Configuration $conf)
    {
        $this->conf = $conf;
        $this->UserModel = new UserModel();
        $this->ConfModel = new ConfModel($this->conf);
        $this->RequestService = new RequestService($this->conf);
    }

    /**
     * @param User $User
     * @return Response
     * @throws Exception
     */
    public function login(User $User)
    {
        $responseAccountAuthorize = $this->accountAuthorize($User);

        //set necessary data to conf;
        $this->conf = $this->ConfModel->setConfAfterAccountAuthorize($responseAccountAuthorize, $User);

        $responseIntegration = $this->accountIntegrationSettings();

        //set necessary data to conf;
        $this->conf = $this->ConfModel->setConfAfterIntegration($responseIntegration);

        $this->checkIfAccountIsActive();

        return new Response([
            'status' => true,
            'message' => '',
            'fields' => ['conf' => $this->conf]
        ]);
    }

    /**
     * @param User $User
     * @return array
     * @throws \SALESmanago\Exception\Exception
     */

    protected function accountAuthorize(User $User)
    {
        $data = $this->UserModel->getUserForAuthorize($User);

        $response = $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::METHOD_LOGIN_AUTHORIZE,
            $data
        );

        return $this->RequestService->validateCustomResponse(
            $response,
            array(array_key_exists(Configuration::TOKEN, $response))
        );
    }

    /**
     * @throws Exception
     * @var Configuration $conf
     * @return array
     */
    protected function accountIntegrationSettings()
    {
        $this->RequestService = new RequestService($this->conf);

        $data = array(
            Configuration::TOKEN   => $this->conf->getToken(),
            Configuration::API_KEY => $this->conf->getApiKey(),
        );

        $response = $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::METHOD_ACCOUNT_INTEGRATION,
            $data
        );

        return $this->RequestService->validateCustomResponse(
            $response,
            array(array_key_exists('shortId', $response))
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function checkIfAccountIsActive()
    {
        try {
            $response = $this->RequestService
                ->request(
                    self::REQUEST_METHOD_POST,
                    self::METHOD_LIST_USERS,
                    $this->ConfModel->getAuthorizationApiDataWithOwner()
                );

            return $this->RequestService->validateResponse($response);
        } catch (Exception $e) {
            $redirectToAppUrl = $this->conf->getEndpoint() . self::METHOD_REDIRECT_TO_APP . $this->conf->getToken();
            throw new Exception('Inactive account', 40, $redirectToAppUrl);
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function refreshToken()
    {
        try {
            $response = $this->RequestService
                ->request(
                    self::REQUEST_METHOD_POST,
                    self::METHOD_REFRESH_TOKEN,
                    $this->ConfModel->getAuthorizationApiDataWithOwner()
                );
            return $this->RequestService->validateResponse($response);
        } catch (\Exception $e) {
            throw new Exception('Inactive account', 40);
        }
    }

    /**
     * @var  $settings
     * @return string - url
     */
    public function getRedirectToAppUrl()
    {
        return $this->conf->getEndpoint() . self::METHOD_REDIRECT_TO_APP . $this->conf->getToken();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function listOwnersEmails()
    {
        $response = $this->RequestService
            ->request(
                self::REQUEST_METHOD_POST,
                self::METHOD_LIST_USERS,
                $this->ConfModel->getAuthorizationApiDataWithOwner()
            );
        return $this->RequestService->validateResponse($response);
    }
}