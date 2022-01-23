<?php
namespace kubokat\ApiWrapper;

interface TransportInterface
{
    public function request($action, $params = []);
}