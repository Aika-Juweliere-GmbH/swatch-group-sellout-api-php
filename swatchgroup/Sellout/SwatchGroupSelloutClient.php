<?php

namespace swatchgroup\Sellout;

/**
 * This is an unofficial Swatch Group Sellout API library for PHP.
 *
 * @author Tom Gottschlich <tom.gottschlich@uhrenlounge.de>
 */
class SwatchGroupSelloutClient
{
    protected const API_PRODUCTIVE_MODE_URL = 'https://sgs-intapip.swatchgroup.biz';
    protected const API_TEST_MODE_URL       = 'https://sgs-intapit.swatchgroup.biz';
    protected const GRANT_TYPE              = 'client_credentials';

    /** @var string $clientId */
    protected string $clientId;
    /** @var string $clientSecret */
    protected string $clientSecret;
    /** @var string $apiIdentifier */
    protected string $apiIdentifier;
    /** @var bool $isProductiveMode */
    protected bool $isProductiveMode;
    /** @var string $accessToken */
    public string $accessToken;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $apiIdentifier
     * @param bool $isProductiveMode
     */
    public function __construct(string $clientId, string $clientSecret, string $apiIdentifier, bool $isProductiveMode = true)
    {
        $this->clientId         = $clientId;
        $this->clientSecret     = $clientSecret;
        $this->apiIdentifier    = $apiIdentifier;
        $this->isProductiveMode = $isProductiveMode;
        $accessData             = $this->getAccessToken($clientId, $clientSecret);
        $this->accessToken      = $accessData->access_token;
    }

    /**
     * Get access token for the api. We have here an own curl function because the body part and other things are differently.
     *
     * @param string $clientId
     * @param string $clientSecret
     *
     * @return object|null
     */
    public function getAccessToken(string $clientId, string $clientSecret): ?object
    {
        $url = ($this->isProductiveMode) ? self::API_PRODUCTIVE_MODE_URL : self::API_TEST_MODE_URL;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url . "/invoke/pub.apigateway.oauth2/getAccessToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=" . self::GRANT_TYPE . "&client_id=" . $clientId . "&client_secret=" . $clientSecret,
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null;
        }

        return json_decode($response);
    }

    /**
     * Sellout function.
     *
     * @param string $salesTransactionId
     * @param string $salesDate
     * @param string $brandCode
     * @param string $posId
     * @param string $posIdType
     * @param string $posCountry
     * @param string $articleCode
     * @param string $serialNumber
     * @param bool $isSellout
     *
     * @return object|null
     */
    public function sellout(
        string $salesTransactionId,
        string $salesDate,
        string $brandCode,
        string $posId,
        string $posIdType,
        string $posCountry,
        string $articleCode,
        string $serialNumber,
        bool $isSellout
    ): ?object
    {
        $selloutData = [
            'salesTransactionId'    => $salesTransactionId,
            'salesDate'             => $salesDate,
            'brandCode'             => $brandCode,
            'POSId'                 => $posId,
            'POSCountry'            => $posCountry,
            'POSIdType'             => $posIdType,
            'articleCode'           => $articleCode,
            'serialNumber'          => $serialNumber,
            'quantity'              => ($isSellout) ? "1" : "-1"
        ];
        return $this->sendCurlRequest(
            '/gateway/sgSellout/1.0/sellout',
            json_encode($selloutData)
        );
    }

    /**
     * Function to build and send the curl request to sellout api.
     *
     * @param string $uri
     * @param string $body
     *
     * @return object|null
     */
    protected function sendCurlRequest(string $uri, string $body): ?object
    {

        $url = ($this->isProductiveMode) ? self::API_PRODUCTIVE_MODE_URL : self::API_TEST_MODE_URL;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL             => $url . $uri,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $body,
            CURLOPT_HTTPHEADER      => [
                "Authorization: Bearer " . $this->accessToken,
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null;
        }

        return json_decode($response);
    }
}
