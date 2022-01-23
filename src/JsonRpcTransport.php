<?php

namespace kubokat\ApiWrapper;

class JsonRpcTransport implements TransportInterface
{
    private $endpoint;
    private $rpcVersion = '2.0';
    private $login = 'demo';
    private $password = 'demo';

    public function __construct($endpoint = 'https://vrdemo.virtreg.ru/vr-api')
    {
        $this->endpoint = $endpoint;
    }

    public function request($action, $params = [])
    {
        $params['auth'] = [
            'login' => $this->login,
            'password' => $this->password
        ];

        $message = json_encode(
            ['jsonrpc' => $this->rpcVersion, 'id' => 1, 'method' => $action, 'params' => $params]
        );

        $requestHeaders = [
            'Content-type: application/json'
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $result = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($result);

        if (!empty($result->error)) {
            throw new \Exception($result->error->message);
        }

        return $result;

    }

}