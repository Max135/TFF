<?php namespace Models\Brokers;

class HotspotBroker extends Broker
{
    //In meters
    private const HOTSPOT_RADIUS = 200;
    private const EARTH_RADIUS_KM = 6378.137;


    //http://mathforum.org/library/drmath/view/61135.html
    //https://stackoverflow.com/questions/2839533/adding-distance-to-a-gps-coordinate
    //https://stackoverflow.com/questions/7477003/calculating-new-longitude-latitude-from-old-n-meters
    //https://math.stackexchange.com/questions/198764/how-to-know-if-a-point-is-inside-a-circle

    //46.057216, -72.824669
    //200m
    //46.055749, -72.825956
    public function createNewHotspot($lastCatchId, $userId)
    {
        $this->getCatchCoordinates($userId);

        $before = hrtime(true);
        echo "<p>Instant Call ".(hrtime(true)-$before)."</p>";
        echo $this->measureAccurately(46.057216, -72.824669, 46.055749, -72.825956) < self::HOTSPOT_RADIUS ? "true" : "false";
        echo "<p>Execution time: ".(hrtime(true)-$before)."</p>";
        exit;
    }

    private function getCatchCoordinates($userId)
    {
        $sql = "select C.coordinates, C.id from Catch C join Trip T on C.tripId = T.id join User U on T.userId = U.id where U.id = ?;";
        $result = $this->select($sql, [$userId]);
        var_dump($result);exit;
        return $result;
    }

    private function isPointInsideRadius($x1, $y1, $x2, $y2)
    {
//        echo "<p>".pow(self::HOTSPOT_RADIUS, 2)." > ".pow($x1 - $x2, 2)." + ".pow($y1 - $y2, 2)."</p>";
//        echo "<p>".(pow($x1 - $x2, 2) + pow($y1 - $y2, 2))."</p>";
        return pow(self::HOTSPOT_RADIUS, 2) >= (pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
    }

    private function measureAccurately($lat1, $lon1, $lat2, $lon2)
    {
        $distanceLat = ($lat2 * (pi()/180)) - ($lat1 * (pi()/180));
        $distanceLon = ($lon2 * (pi()/180)) - ($lon1 * (pi()/180));

        $a = pow($this->sine($distanceLat), 2) + ($this->cosine($lat1) * $this->cosine($lat2) * pow($this->sine($distanceLon), 2));

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        $distance = self::EARTH_RADIUS_KM * $c;

        return $distance * 1000;
    }

    private function cosine($value)
    {
        return cos($value * (pi()/180));
    }

    private function sine($value)
    {
        return sin($value/2);
    }
}