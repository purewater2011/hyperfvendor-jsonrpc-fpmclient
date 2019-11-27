<?php

namespace HyperfVendor;

use Hyperf\Utils\Str;

class Generator
{
    public function generateData(string $serviceName, string $methodName, array $params, ?string $id = null)
    {
        $formatter = new DataFormatter();
        return $formatter->formatRequest([$this->generateRpcPath($serviceName, $methodName), $params, $id]);
    }

    public function generateRpcPath(string $serviceName, string $methodName)
    {
        $handledNamespace = explode('\\', $serviceName);
        $handledNamespace = Str::replaceArray('\\', ['/'], end($handledNamespace));
        $handledNamespace = Str::replaceLast('Service', '', $handledNamespace);
        $path = Str::snake($handledNamespace);

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }
        return $path . '/' . $methodName;
    }
}