<?php

namespace GT\Api\Service;

use GT\Api\Exception\SoapException;
use Exception;
use SoapClient;

class ApiService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Processes soap requests based on WSDL.
     *
     * @param string $wsdl
     * @param string $requestName
     * @param array $parameters
     * @param bool $normalize
     * @throws SoapException
     * @return array|object
     */
    public function processRequest(string $wsdl, string $requestName, array $parameters = [], bool $normalize = false): array
    {
        try {
            $client = new SoapClient($wsdl);
            $soapResponse['raw'] = $client->__soapCall($requestName, $parameters);
            !$normalize ?: $soapResponse['normalized'] = $this->normalizeResponse($soapResponse['raw']);

            return $soapResponse;
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new SoapException('Error on line ' . $exception->getLine() . ' in ' . $exception->getFile() . '.Exception : ' . $exception->getMessage(), 400, $exception);
        }
    }

    /**
     * Normalizes soap response to structured array.
     *
     * @param array|object $response
     * @return array
     */
    public function normalizeResponse($response): array
    {
        $normalizedResponse = [];
        libxml_use_internal_errors(true);
        $this->createNormalizedArray($response, $normalizedResponse);
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        return $normalizedResponse;
    }

    /**
     * Creates normalized array.
     *
     * @param object|mixed $obj
     * @param array|mixed $response
     * @return array|mixed
     */
    public function createNormalizedArray($obj, &$response)
    {
        if (is_object($obj)) $obj = (array) $obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = $this->createNormalizedArray($val, $response);
            }
        } else {
            if (is_string($obj)) {
                $array = $this->convertStringToXml($obj);
                $array == null ?: $response[] = $array;
            }
            $new = $obj;
        }

        return $new;
    }

    /**
     * Converts XML string to array.
     *
     * @param string $value
     * @return array|null
     */
    private function convertStringToXml(string $value)
    {
        $response = null;
        $xml = simplexml_load_string($value, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml) {
            $response = json_decode(json_encode((array)$xml), TRUE);
        }

        return $response;
    }
}
