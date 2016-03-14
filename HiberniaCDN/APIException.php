<?php
/**
 * Exception wrapper-object for API errors handling
 */
namespace HiberniaCDN;

class APIException extends \Exception
{
    /**
     * Raw API JSON-string response
     * @var string
     */
    protected $apiResponse = '';

    /**
     * API response HTTP status: 200, 401, 404, 500 etc
     * @var int
     */
    protected $apiResponseStatus = 0;

    /**
     * API response error text
     * @var string
     */
    protected $serverErrorMessage = '';

    /**
     * API response error detailed description (if given)
     * @var string
     */
    protected $serverErrorDetails = '';

    /**
     * @return string
     */
    public function getApiResponse()
    {
        return $this->apiResponse;
    }

    /**
     * @param string $apiResponse
     * @return $this
     */
    public function setApiResponse($apiResponse)
    {
        $this->apiResponse = $apiResponse;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerErrorMessage()
    {
        return $this->serverErrorMessage;
    }

    /**
     * @param string $serverErrorMessage
     * @return $this
     */
    public function setServerErrorMessage($serverErrorMessage)
    {
        $this->serverErrorMessage = $serverErrorMessage;
        return $this;

    }

    /**
     * @return string
     */
    public function getServerErrorDetails()
    {
        return $this->serverErrorDetails;
    }

    /**
     * @param string $serverErrorDetails
     * @return $this
     */
    public function setServerErrorDetails($serverErrorDetails)
    {
        $this->serverErrorDetails = $serverErrorDetails;
        return $this;
    }

    /**
     * @return int
     */
    public function getApiResponseStatus()
    {
        return $this->apiResponseStatus;
    }

    /**
     * @param int $apiResponseStatus
     * @return $this
     */
    public function setApiResponseStatus($apiResponseStatus)
    {
        $this->apiResponseStatus = $apiResponseStatus;
        return $this;
    }

}