<?php


namespace cy322666\AlfaCRM;


use cy322666\AlfaCRM\Base\Curl;

class Api
{
    private $attributes = [

    ];

    private $url = '/auth/login';
    private $authorization = false;
    private $token = '';

    private $access = [
        'email' => '',
        'api_key' => ''
    ];

    public function __construct($access)
    {
        $this->initModels();
        $this->initAccess($access);
        $this->Curl = new Curl($access['subdomain']);
    }

    private function initModels()
    {
        foreach ($this->attributes as $key => $attribute) {
            $className = __NAMESPACE__.'\Models\\'.$key;

            $this->$key = new $className;
        }
    }
    private function initAccess($access)
    {
        if($access) {
            $this->access['email'] = $access['email'];
            $this->access['api_key'] = $access['api_key'];
        }
    }

    public function auth()
    {
        $Response = Curl::Query($this->url, [
            'email' => $this->access['email'],
            'api_key' => $this->access['api_key']
        ]);
        if($Response != false) {
            $this->token = $Response;
            $this->authorization = true;
        }
    }


}