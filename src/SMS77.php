<?php

namespace NotificationChannels\SMS77;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHTtp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use NotificationChannels\SMS77\Exceptions\CouldNotSendNotification;

class SMS77
{
    /**
     * @var string Seven API URL.
     */
    protected string $apiUrl = 'https://gateway.seven.io/api/';

    /**
     * @param  string  $apiKey
     * @param  HttpClient  $http
     */
    public function __construct(
        protected string|null $apiKey = null,
        protected HttpClient|null $http = null
    ) {
    }

    /**
     * Get API key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set API key.
     *
     * @param  string  $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    /**
     * Send text message.
     *
     * <code>
     * $params = [
     *      'to'                    => '',
     *      'text'                  => '',
     *      'from'                  => '',
     *      'delay'                 => '',
     *      'flash'                 => '',
     *      'udh'                   => '',
     *      'ttl'                   => '',
     *      'label'                 => '',
     *      'json'                  => '',
     *      'performance_tracking'  => ''
     * ];
     * </code>
     *
     * @link https://docs.seven.io/en/rest-api/endpoints/sms#send-sms
     *
     * @param  array  $params
     */
    public function sendMessage(array $params)
    {
        return $this->sendRequest('sms', $params);
    }

    /**
     * @throws GuzzleException
     * @throws CouldNotSendNotification
     */
    public function sendRequest(string $endpoint, array $params)
    {
        if (empty($this->apiKey)) {
            throw CouldNotSendNotification::apiKeyNotProvided();
        }

        try {
            return $this->httpClient()->post($this->apiUrl.$endpoint, [
                'headers' => [
                    'Authorization' => 'basic '.$this->apiKey,
                ],
                'form_params' => $params,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceNotAvailable($exception);
        }
    }
}
