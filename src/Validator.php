<?php
namespace kubokat\ApiWrapper;

class Validator implements ValidatorInterface
{
    private $keys;
    private $params;

    public function validate($params, $keys)
    {
        $this->params = $params;
        $this->keys = $keys;

        foreach ($this->keys as $key) {
            if (empty($this->params[$key])) {
                throw new \Exception("${key} parameter is required");
            }
        }

        return $this;
    }

    public function clear()
    {
        foreach ($this->params as $k => &$v) {
            if (!in_array($k, $this->keys)) {
                unset($this->params[$k]);
            }
        }

        return $this->params;
    }
}