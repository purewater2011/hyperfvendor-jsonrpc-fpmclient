<?php
/**
 * Created by PhpStorm.
 * User: yd-yf-2018091401-001
 * Date: 2019/11/27
 * Time: 5:18 PM
 */

namespace HyperfVendor;


class Packer
{
    public function pack($data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function unpack(string $data)
    {
        return json_decode($data, true);
    }
}