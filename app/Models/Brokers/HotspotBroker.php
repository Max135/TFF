<?php namespace Models\Brokers;

class HotspotBroker extends Broker
{
    //http://mathforum.org/library/drmath/view/61135.html
    //https://stackoverflow.com/questions/2839533/adding-distance-to-a-gps-coordinate
    //https://stackoverflow.com/questions/7477003/calculating-new-longitude-latitude-from-old-n-meters
    //https://www.mathsisfun.com/algebra/circle-equations.html

    //46.057216, -72.824669
    //200m
    //46.055749, -72.825956

    //note: php sin, cos and tan functions use radians
    public function createNewHotspot($lastCatchId)
    {

    }
}