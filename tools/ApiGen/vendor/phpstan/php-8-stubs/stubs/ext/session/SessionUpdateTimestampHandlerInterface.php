<?php 

interface SessionUpdateTimestampHandlerInterface
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public function validateId(string $id);
    /**
     * @tentative-return-type
     * @return bool
     */
    public function updateTimestamp(string $id, string $data);
}