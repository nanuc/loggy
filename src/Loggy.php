<?php

namespace Nanuc\Loggy;

use Nanuc\Loggy\Exceptions\LoggyException;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class Loggy
{
    protected $url;
    protected $key;
    protected $measurements = [];

    private $cloner;
    private $dumper;

    public function __construct($url, $key)
    {
        if(strlen($key) != config('loggy.key-length')) {
            throw new LoggyException('Key must have a length of ' . config('loggy.key-length') . ' characters');
        }
        $this->url = $url;
        $this->key = $key;

        $this->cloner = new VarCloner();
        $this->dumper = new HtmlDumper();
    }

    /**
     * @param $arguments
     */
    public function send(...$arguments)
    {
        foreach ($arguments as $message) {
            $pretty = $this->convertToString($message);

            $this->postWithoutWait($this->url . '/' . $this->key, [
                'message' => $message,
                'pretty' => $pretty,
                'runtime' => defined('LARAVEL_START') ? microtime(true) - LARAVEL_START : null,
                'app' => [
                    'name' => config('app.name'),
                    'env' => config('app.env'),
                ]
            ]);
        }
    }

    /**
     * @param $name
     */
    public function startMeasurement($name = 'Measurement')
    {
        $this->measurements[$name] = microtime(true);
    }

    /**
     * @param $name
     */
    public function stopMeasurement($name = 'Measurement')
    {
        $time = microtime(true);

        if(array_key_exists($name, $this->measurements)) {
            $this->send('[' . $name . '] ' .  number_format($time - $this->measurements[$name], 4));
        }
        else {
            $this->send('[' . $name . '] Measurement with this name has not been started.');
        }
    }

    /**
     *
     * @param $url
     * @param $params
     */
    protected function postWithoutWait($url, $params)
    {
        $parts=parse_url($url);
        $data= json_encode($params);

        $fp = fsockopen('tls://'.$parts['host'], 443, $errno, $errstr, 30);

        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/json\r\n";
        $out.= "Content-Length: ".strlen($data)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        $out.= $data;

        fwrite($fp, $out);
        fclose($fp);
    }

    protected function convertToString($argument): string
    {
        $clonedArgument = $this->cloner->cloneVar($argument);

        return $this->dumper->dump($clonedArgument, true);
    }
}