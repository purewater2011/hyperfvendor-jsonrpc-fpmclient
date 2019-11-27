<?php
/**
 * Created by PhpStorm.
 * User: yd-yf-2018091401-001
 * Date: 2019/11/27
 * Time: 4:52 PM
 */

namespace HyperfVendor;


class Node
{
    private $nodes;

    public function getNode()
    {
        if (!$this->nodes) {
            $this->setNodes();
        }
        return $this->nodes[array_rand($this->nodes)];
    }

    public function setNodes(?array $nodes = []): self
    {
        if (!$nodes) {
            $default = __DIR__ . '/config/nodes.php';
            $configFile = '';
            if (defined('BASE_PATH') || defined('ROOT_PATH')) {
                defined('BASE_PATH') && $configFile = BASE_PATH . '/config/nodes.php';
                defined('ROOT_PATH') && $configFile = ROOT_PATH . '/config/nodes.php';
            }
            if (file_exists($configFile)) {
                $nodes = include_once $configFile;
            } else {
                $nodes = include_once $default;
            }
        }
        if (!$nodes) {
            throw new \RuntimeException('not found node config');
        }
        $this->nodes = $nodes;
        return $this;
    }
}