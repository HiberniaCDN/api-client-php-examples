<?php
/**
 * Simple HTTP-Requests interaction for API Client
 */
namespace HiberniaCDN\APIClient;

class HTTPClient
{
    /**
     * API endpoint: https://portal.hiberniacdn.com/api
     * @var string
     */
    protected $apiURL = 'https://portal.hiberniacdn.com/api';

    /**
     * APIHTTPClient constructor.
     * @param $apiURL
     */
    public function __construct($apiURL = 'https://portal.hiberniacdn.com/api')
    {
        $this->apiURL = $apiURL;
    }

    /**
     * Basic method that sends HTTP requests using CURL
     * 
     * @param string $method Method name: 'GET', 'POST', 'PUT', 'DELETE'
     * @param string $url Request URL
     * @param array $options Request options including request parameters as [..., 'params' => [...], ...]
     * @return mixed
     */
    protected function httpSend($method, $url, $options)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }
        if ($method == 'POST' || $method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            if (!empty($options['params'])) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $options['params']);
            }
        }
        $response = curl_exec($curl);
        if (!$response) {
            die("Connection failed");
        }
        return $response;
    }

    /**
     * Sets up proper headers for API request:
     *  + Sets up Authorization if possible 
     *  + Sets up Content-Type
     * 
     * @param string $authToken Authorization Token
     * @param array $headers Any extra headers
     * @return array
     */
    protected function prepareHeaders($authToken = '', $headers = [])
    {
        $headers[] = 'Content-Type: application/json';
        if (!empty($authToken)) {
            $headers[] = 'Authorization: Bearer ' . $authToken;
        }
        return $headers;
    }

    /**
     * Processes API response trying to convert it to Array
     * 
     * @param string $rawResponse API response JSON-string
     * @return mixed|null
     * @throws Exception
     */
    protected function parseAPIResponse($rawResponse)
    {
        if (!empty($rawResponse)) {
            $decoded = json_decode($rawResponse, true);
            if (!empty($decoded['errors'])) {
                $exception = new Exception();
                $exception->setApiResponse($rawResponse);
                if ($decoded['errors'][0] && $decoded['errors'][0]['title']) {
                    $exception
                        ->setApiResponseStatus($decoded['errors'][0]['status'])
                        ->setServerErrorMessage($decoded['errors'][0]['title'])
                        ->setServerErrorDetails($decoded['errors'][0]['detail'])
                    ;
                }
                throw $exception;
            }
            return $decoded;
        }
        return null;
    }

    /**
     * HTTP GET Implementation
     * 
     * @param string $uri API URI (example: '/accounts/{ID}')
     * @param string $authToken Authorization Token
     * @return mixed|null
     * @throws Exception
     */
    public function get($uri, $authToken = '')
    {
        return $this->parseAPIResponse(
            $this->httpSend('GET',
                $this->apiURL . $uri,
                [
                    'headers' => $this->prepareHeaders($authToken)
                ])
        );
    }

    /**
     * HTTP POST Implementation
     *
     * @param string $uri API URI (example: '/accounts/{ID}')
     * @param array $parameters Request parameters
     * @param string $authToken Authorization Token
     * @return mixed|null
     * @throws Exception
     */
    public function post($uri, $parameters, $authToken = '')
    {
        return $this->parseAPIResponse(
            $this->httpSend('POST',
                $this->apiURL . $uri,
                [
                    'params' => json_encode($parameters),
                    'headers' => $this->prepareHeaders($authToken)
                ])
        );
    }

    /**
     * HTTP PUT Implementation
     *
     * @param string $uri API URI (example: '/accounts/{ID}')
     * @param array $parameters Request parameters
     * @param string $authToken Authorization Token
     * @return mixed|null
     * @throws Exception
     */
    public function put($uri, $parameters, $authToken = '')
    {
        return $this->parseAPIResponse(
            $this->httpSend('PUT',
                $this->apiURL . $uri,
                [
                    'params' => json_encode($parameters),
                    'headers' => $this->prepareHeaders($authToken)
                ])
        );
    }

    /**
     * HTTP DELETE Implementation
     *
     * @param string $uri API URI (example: '/sites/{ID}')
     * @param string $authToken Authorization Token
     * @return mixed|null
     * @throws Exception
     */
    public function delete($uri, $authToken = '')
    {
        return $this->parseAPIResponse(
            $this->httpSend('DELETE',
                $this->apiURL . $uri,
                [
                    'headers' => $this->prepareHeaders($authToken)
                ])
        );
    }
}
