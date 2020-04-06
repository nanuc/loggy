<?php

namespace Nanuc\Loggy;

use Nanuc\Loggy\Exceptions\LoggyException;

class Loggy
{
    protected $url;
    protected $key;

    public function __construct($url, $key)
    {
        if(strlen($key) != config('loggy.key-length')) {
            throw new LoggyException('Key must have a length of ' . config('loggy.key-length') . ' characters');
        }
        $this->url = $url;
        $this->key = $key;
    }

    /**
     * @param $message
     */
    public function send($message)
    {
        $this->postWithoutWait($this->url . '/' . $this->key, [
            'message' => $message,
            'runtime' => defined('LARAVEL_START') ? microtime(true) - LARAVEL_START : null,
            'app' => [
                'name' => config('app.name'),
                'env' => config('app.env'),
            ]
        ]);
    }

    /**
     *
     * @param $url
     * @param $params
     */
    public function postWithoutWait($url, $params)
    {
        $parts=parse_url($url);
        $data= json_encode($params);

        $fp = fsockopen($parts['host'],
            isset($parts['port'])?$parts['port']:80,
            $errno, $errstr, 30);

        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/json\r\n";
        $out.= "Content-Length: ".strlen($data)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        $out.= $data;

        fwrite($fp, $out);
        fclose($fp);
    }
}