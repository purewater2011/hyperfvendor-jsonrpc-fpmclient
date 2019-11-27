<?php

namespace HyperfVendor;


class DataFormatter
{
    public function formatRequest($data)
    {
        [$path, $params, $id] = $data;
        return [
            'jsonrpc' => '2.0',
            'method' => $path,
            'params' => $params,
            'id' => $id,
        ];
    }

    public function formatResponse($data)
    {
        [$id, $result] = $data;
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => $result,
        ];
    }

    public function formatErrorResponse($data)
    {
        [$id, $code, $message, $data] = $data;

        if (isset($data) && $data instanceof \Exception) {
            $data = [
                'class' => get_class($data),
                'message' => $data->getMessage(),
            ];
        }
        return [
            'jsonrpc' => '2.0',
            'id' => $id ?? null,
            'error' => [
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ],
        ];
    }
}