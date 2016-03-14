<?php

include_once 'HiberniaCDN/APIHTTPClient.php';

$email = '';
$password = '';

try {
    # Create a client
    $client = new \HiberniaCDN\APIHTTPClient();

    # Logging in
    $response = $client->post('/login', ['email' => $email,'password' => $password]);

    # Saving Auth Token
    $authToken = $response['bearer_token'];

    # Request Account's sites for returned User's account
    $sites = $client->get(
        '/accounts/' . $response['user']['account']['id'] . '/sites',
        $authToken
    );
    echo sizeof($sites) . ' sites found' . PHP_EOL;

} catch (\HiberniaCDN\APIException $x) {
    echo 'Error!' . PHP_EOL;
    echo ' > Status: ' . $x->getApiResponseStatus() . PHP_EOL;
    echo ' > Text: ' . $x->getServerErrorMessage() . PHP_EOL;
    echo ' > Details: ' . $x->getServerErrorDetails() . PHP_EOL;
    echo ' > Raw Response: ' . $x->getApiResponse() . PHP_EOL;
}

