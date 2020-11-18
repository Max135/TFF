<?php


namespace Controllers;


use Models\Brokers\CatchBroker;
use Models\Brokers\HotspotBroker;

class TestController extends \Zephyrus\Security\Controller
{

    /**
     * @inheritDoc
     */
    public function initializeRoutes()
    {
        $this->get('/test', 'test');

        $this->get('/testHotspot', 'testHotspot');
        $this->post('/testHotspot', 'testHotspotPost');
    }

    public function test()
    {
        (new HotspotBroker())->createNewHotspot(1);
    }

    public function testHotspot()
    {
        return $this->render('AddHotspotTest', ['title' => 'Test']);
    }

    public function testHotspotPost()
    {
        $form = $this->buildForm();

        $catchId = (new CatchBroker())->insert(3, 1, 1,
            1, '2008-11-11 00:00:00', $form->getValue('lon'), $form->getValue('lat'));

        (new HotspotBroker())->createNewHotspot($catchId);

        return $this->redirect('testHotspot');
    }
}