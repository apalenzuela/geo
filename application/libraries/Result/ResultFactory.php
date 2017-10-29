<?php

// namespace UAParser\Result;

include_once APPPATH."libraries/Result/Result.php";
include_once APPPATH."libraries/Result/ResultFactoryInterface.php";

/**
 * @author Benjamin Laugueux <benjamin@yzalis.com>
 */
class ResultFactory implements ResultFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    final public function createFromArray(array $data)
    {
        $result = $this->newInstance();
        $result->fromArray($data);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function newInstance()
    {
        return new Result();
    }
}
