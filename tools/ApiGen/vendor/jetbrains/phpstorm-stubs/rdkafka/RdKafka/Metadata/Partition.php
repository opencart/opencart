<?php

namespace RdKafka\Metadata;

class Partition
{
    /**
     * @return int
     */
    public function getId() {}

    /**
     * @return mixed
     */
    public function getErr() {}

    /**
     * @return mixed
     */
    public function getLeader() {}

    /**
     * @return mixed
     */
    public function getReplicas() {}

    /**
     * @return mixed
     */
    public function getIsrs() {}
}
