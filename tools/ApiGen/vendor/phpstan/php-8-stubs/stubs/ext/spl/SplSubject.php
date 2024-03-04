<?php 

interface SplSubject
{
    /**
     * @tentative-return-type
     * @return void
     */
    public function attach(SplObserver $observer);
    /**
     * @tentative-return-type
     * @return void
     */
    public function detach(SplObserver $observer);
    /**
     * @tentative-return-type
     * @return void
     */
    public function notify();
}