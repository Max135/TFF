<?php namespace Models\Brokers;

class HotspotBroker extends Broker
{
    //In meters
    private const HOTSPOT_RADIUS = 200;
    private const EARTH_RADIUS_KM = 6378.137;

    //hrtime(true); to get execution time in nanoseconds

    //http://mathforum.org/library/drmath/view/61135.html
    //https://stackoverflow.com/questions/2839533/adding-distance-to-a-gps-coordinate
    //https://stackoverflow.com/questions/7477003/calculating-new-longitude-latitude-from-old-n-meters
    //https://math.stackexchange.com/questions/198764/how-to-know-if-a-point-is-inside-a-circle

    //46.057216, -72.824669
    //200m
    //46.055749, -72.825956
    public function createNewHotspot($lastCatchId, $userId)
    {
        $currentCoordinates = $this->getCatchCoordinates($lastCatchId);
        foreach ($this->getHotspots($userId) as $hotspot) {
            if ($this->measureAccurately($currentCoordinates->lat, $currentCoordinates->lon, $hotspot->lat, $hotspot->lon)) {
                $sql = "update Catch set hotspotId = ? where id = ?;";
                $this->query($sql, [$hotspot->id, $lastCatchId]);
                return;
            }
        }

        //TODO: Confirm what to do here (Check all other coordinates...)
        foreach ($this->getCatchAloneView() as $catch) {
            if ($this->measureAccurately($currentCoordinates->lat, $currentCoordinates->lon, $catch->lat, $catch->lon)) {

            }
        }
    }

    public function getUsersHotspots($userId) {

    }

    private function getCatchCoordinates($catchId)
    {
        $sql = "select X(C.coordinates) as lat, Y(C.coordinates) as lon from Catch C where id = ?;";
        return $this->selectSingle($sql, [$catchId]);
    }

    private function getHotspots($userId)
    {
        $sql = "select id, X(coordinates) as lat, Y(coordinates) as lon from Hotspot where userId = ?;";
        return $this->select($sql, [$userId]);
    }

    private function getCatchAloneView()
    {
        $sql = "select * from CatchAlone;";
        return $this->select($sql);
    }

    //Haversine formula
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

    private function isPointInsideRadius($x1, $y1, $x2, $y2)
    {
//        echo "<p>".pow(self::HOTSPOT_RADIUS, 2)." > ".pow($x1 - $x2, 2)." + ".pow($y1 - $y2, 2)."</p>";
//        echo "<p>".(pow($x1 - $x2, 2) + pow($y1 - $y2, 2))."</p>";
        return pow(self::HOTSPOT_RADIUS, 2) >= (pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
    }
}