<?php

namespace HyperfVendor;

use RuntimeException;
use Swoole\Client as SwooleClient;

class Service
{
    public $options = [
        'open_eof_check' => true,
        'package_eof' => "\r\n",
        'package_max_length' => 1024 * 1024 * 2,
        'open_length_check'     => 1,
    ];

    private $connectTimeout = 3;

    private $recvTimeout = 3;

    private $client;

    public function __construct($serviceName)
    {
        $this->serviceName = $serviceName;
    }
    public static function getInstance(string $serviceName)
    {
        return new static($serviceName);
    }

    public function call(string $method, array $params)
    {
        $this->client = $this->getClient();
        $generate = new Generator();
        $data = $generate->generateData($this->serviceName, $method, $params);
        try {
            $packer = new Packer();
            $data = $packer->pack($data);
            if ($this->client->send($data . $this->getEof()) === false) {
                if ($this->client->errCode == 104) {
                    throw new RuntimeException('Connect to server failed.');
                }
            }
        } catch (\Throwable $throwable) {
            $this->client->close();
            throw $throwable;
        }
        return $this;
    }

    public function getResult($timeout = 3)
    {
        $this->recvTimeout = $timeout;
        try {
            $data = $this->client->recv($this->recvTimeout);
        } finally {
            $this->client->close();
        }
        $packer = new Packer();
        $data = $packer->unpack($data);
        return $data;
    }

    public function send(string $data)
    {
        $client = $this->getClient();
        try {
            if ($client->send($data . $this->getEof()) === false) {
                if ($client->errCode == 104) {
                    throw new RuntimeException('Connect to server failed.');
                }
            }
        } catch (\Throwable $throwable) {
            $client->close();
            throw $throwable;
        }
        try {
            $data = $client->recv($this->recvTimeout);
        } finally {
            $client->close();
        }
        return $data;
    }

    public function getClient()
    {
        $client = new SwooleClient(SWOOLE_SOCK_TCP);
        $client->set($this->options);
        $node = new Node();
        $node = $node->getNode();
        $result = $client->connect($node['host'], $node['port'], $this->connectTimeout);
        if ($result === false && ($client->errCode == 114 or $client->errCode == 115)) {
            // Force close and reconnect to server.
            $client->close();
            throw new RuntimeException('Connect to server failed.');
        }
        return $client;
    }

    protected function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    private function getEof()
    {
        return "\r\n";
    }
}