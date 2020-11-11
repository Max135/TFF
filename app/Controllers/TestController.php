<?php


namespace Controllers;


use Models\Brokers\HotspotBroker;

class TestController extends \Zephyrus\Security\Controller
{

    /**
     * @inheritDoc
     */
    public function initializeRoutes()
    {
        $this->get('/test', 'test');
    }

    public function test()
    {
        (new HotspotBroker())->createNewHotspot(1, 1);
    }
}