<?php 

class SplQueue extends \SplDoublyLinkedList
{
    /**
     * @tentative-return-type
     * @implementation-alias SplDoublyLinkedList::push
     * @return void
     */
    public function enqueue(mixed $value)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias SplDoublyLinkedList::shift
     * @return mixed
     */
    public function dequeue()
    {
    }
}