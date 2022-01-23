<?php
namespace kubokat\ApiWrapper;

interface ValidatorInterface
{
    public function validate($params, $keys);
    public function clear();
}