<?php

namespace AbelAguiar\Cielo;

use GuzzleHttp\Client as Guzzle;
use Ramsey\Uuid\Uuid;

class CieloClient
{
    const PRODUCTION_ENDPOINT = 'https://api.cieloecommerce.cielo.com.br';
    const PRODUCTION_QUERY_ENDPOINT = 'https://apiquery.cieloecommerce.cielo.com.br';
    const SANDBOX_ENDPOINT = 'https://apisandbox.cieloecommerce.cielo.com.br';
    const SANDBOX_QUERY_ENDPOINT = 'https://apiquerysandbox.cieloecommerce.cielo.com.br';

    private $guzzle;
    private $requestId;

    public function __construct($production = true)
    {
        $endpoint = ($production) ? self::PRODUCTION_ENDPOINT : self::SANDBOX_ENDPOINT;
        $queryEndpoint = ($production) ? self::PRODUCTION_QUERY_ENDPOINT : self::SANDBOX_QUERY_ENDPOINT;

        $this->perform = new Guzzle([
            'base_uri' => $endpoint,
        ]);
        $this->consult = new Guzzle([
            'base_uri' => $queryEndpoint,
        ]);

        $this->requestId = Uuid::Uuid4();
    }

    /**
     * Performs a new transaction.
     *
     * @param Cielo $cielo
     *
     * @return stdClass
     */
    public function performTransaction(Cielo $cielo)
    {
        $headers = [
            'MerchantId'  => $cielo->getMerchantId(),
            'MerchantKey' => $cielo->getMerchantKey(),
            'RequestId'   => $this->requestId,
        ];

        $json = [
            'MerchantOrderId' => Uuid::Uuid4(),
            'Customer'        => $cielo->getCustomer()->toArray(),
            'Payment'         => $cielo->getPaymentMethod()->toArray(),
        ];

        $res = $this->perform->post('/1/sales', compact('headers', 'json'));

        return json_decode($res->getBody()->getContents());
    }

    /**
     * Consult a transaction by the Payment ID.
     *
     * @param Cielo  $cielo
     * @param string $paymentId
     *
     * @return stdClass
     */
    public function consultTransaction(Cielo $cielo, $paymentId)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'MerchantId'   => $cielo->getMerchantId(),
            'MerchantKey'  => $cielo->getMerchantKey(),
            'RequestId'    => $this->requestId,
        ];

        $res = $this->consult->get('/1/sales/'.$paymentId, compact('headers'));

        return json_decode($res->getBody()->getContents());
    }

    /**
     * Capture a transaction by the Payment ID.
     *
     * @param Cielo  $cielo
     * @param string $paymentId
     *
     * @return stdClass
     */
    public function captureTransaction(Cielo $cielo, $paymentId)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'MerchantId'   => $cielo->getMerchantId(),
            'MerchantKey'  => $cielo->getMerchantKey(),
        ];

        $res = $this->perform->put('/1/sales/'.$paymentId.'/capture', compact('headers'));

        return json_decode($res->getBody()->getContents());
    }
}
