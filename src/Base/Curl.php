<?php


namespace cy322666\AlfaCRM\Base;


class Curl
{
    private static $subdomain;

    public function __construct($subdomain)
    {
        self::$subdomain = $subdomain;
    }

    public static function Query($link, $array)
    {
        $headers = self::getHeaders($link);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, 'https://'.self::$subdomain.'.s20.online/v2api'.$link);
        if($array) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($array));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        }
        $Response = json_decode(curl_exec($curl), true);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl))
            throw new \Exception('Curl error');

        curl_close($curl);

        if ($code !== 200)
            throw new \Exception($Response['name'] . ' - ' . $Response['message']);

        $Response = self::getResponse($link, $Response);

        return $Response;
    }

    private static function getHeaders($link)
    {
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';

        return $headers;
    }

    private static function getResponse($link, $Response)
    {
        switch ($link) {//200 /
            case '/auth/login':
                if($Response['token']) {
                    $Response = $Response['token'];
                } else {
                    $Response = false;
                }
                break;
        }
        return $Response;
    }
}