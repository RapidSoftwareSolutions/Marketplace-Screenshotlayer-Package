<?php
$app->post('/api/Screenshotlayer/captureSnapshot', function ($request, $response, $args) {
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'url']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }
    //forming request to vendor API
    $query_str = $settings['api_url'] . "capture";
    $body = array();
    $body['access_key'] = $post_data['args']['apiKey'];
    $body['url'] = $post_data['args']['url'];

    if (isset($post_data['args']['fullPage']) && strlen($post_data['args']['fullPage']) > 0) {
        $body['fullpage'] = $post_data['args']['fullPage'];
    }
    if (isset($post_data['args']['thumbnailsWidth']) && strlen($post_data['args']['thumbnailsWidth']) > 0) {
        $body['width'] = $post_data['args']['thumbnailsWidth'];
    }
    if (isset($post_data['args']['viewportControl']) && strlen($post_data['args']['viewportControl']) > 0) {
        $body['viewport'] = $post_data['args']['viewportControl'];
    }
    if (isset($post_data['args']['outputFormat']) && strlen($post_data['args']['outputFormat']) > 0) {
        $body['format'] = $post_data['args']['outputFormat'];
    } else {
        $body['format'] = 'png';
    }
    if (isset($post_data['args']['secretKeyword']) && strlen($post_data['args']['secretKeyword']) > 0) {
        $body['secret_key'] = md5($post_data['args']['url'] . $post_data['args']['secretKeyword']);
    }
    if (isset($post_data['args']['cssUrl']) && strlen($post_data['args']['cssUrl']) > 0) {
        $body['css_url'] = $post_data['args']['cssUrl'];
    }
    if (isset($post_data['args']['captureDelay']) && strlen($post_data['args']['captureDelay']) > 0) {
        $body['delay'] = $post_data['args']['captureDelay'];
    }
    if (isset($post_data['args']['cachingTime']) && strlen($post_data['args']['cachingTime']) > 0) {
        $body['ttl'] = $post_data['args']['cachingTime'];
    }
    if (isset($post_data['args']['forceRefresh']) && strlen($post_data['args']['forceRefresh']) > 0) {
        $body['force'] = $post_data['args']['forceRefresh'];
    }
    if (isset($post_data['args']['placeholderImage']) && strlen($post_data['args']['placeholderImage']) > 0) {
        $body['placeholder'] = $post_data['args']['placeholderImage'];
    }
    if (isset($post_data['args']['userAgent']) && strlen($post_data['args']['userAgent']) > 0) {
        $body['user_agent'] = $post_data['args']['userAgent'];
    }
    if (isset($post_data['args']['acceptLanguage']) && strlen($post_data['args']['acceptLanguage']) > 0) {
        $body['accept_lang'] = $post_data['args']['acceptLanguage'];
    }
    if (isset($post_data['args']['exportTo']) && strlen($post_data['args']['exportTo']) > 0) {
        $body['export'] = $post_data['args']['exportTo'];
    }

    //requesting remote API
    $client = new GuzzleHttp\Client();
    $result = [];

    $client->getAsync($query_str,
        [

            'query' => $body,
            'stream' => true
        ]
    )
        ->then(
            function (\Psr\Http\Message\ResponseInterface $response) use ($client, $post_data, $settings, &$result, &$body) {

                $responseApi = $response->getBody()->getContents();
                $errorSet = json_decode($responseApi, true);
                $size = strlen($responseApi);
                if (in_array($response->getStatusCode(), ['200', '201', '202', '203', '204']) && $errorSet === null) {
                    try {
                        $fileUrl = $client->post($settings['uploadServiceUrl'], [
                            'multipart' => [
                                [
                                    'name' => 'length',
                                    'contents' => $size
                                ],
                                [
                                    'name' => 'file',
                                    'filename' => $body['url'] . '.' . $body['format'],
                                    'contents' => $responseApi
                                ],
                            ]
                        ]);
                        $gcloud = $fileUrl->getBody()->getContents();
                        $resultDecoded = json_decode($gcloud, true);
                        $result['callback'] = 'success';
                        $result['contextWrites']['to'] = ($resultDecoded != NULL) ? $resultDecoded : $gcloud;
                    } catch (GuzzleHttp\Exception\BadResponseException $exception) {
                        $result['callback'] = 'error';
                        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
                        $result['contextWrites']['to']['status_msg'] = 'Something went wrong during file link receiving.';
                    }
                } else {
                    $resultDecoded = json_decode($responseApi, true);
                    $result['callback'] = 'error';
                    $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                    $result['contextWrites']['to']['status_msg'] = ($resultDecoded != NULL) ? $resultDecoded : $responseApi;
                }
            },
            function (GuzzleHttp\Exception\BadResponseException $exception) use (&$result) {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                $result['contextWrites']['to']['status_msg'] = $exception->getMessage();
            },
            function (GuzzleHttp\Exception\ConnectException $exception) use (&$result) {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
                $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';
            }
        )
        ->wait();


    return $response->withHeader('Content-type', 'application/json')->withJson($result, 200, JSON_UNESCAPED_SLASHES);


});