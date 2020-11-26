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
    public function createNewHotspot($lastCatchId)
    {
        $currentCoordinates = $this->getCatchInfo($lastCatchId);
        $userId = $currentCoordinates->id;
        $isInHotspot = $this->insertCatchToExistingHotspot($lastCatchId, $userId, $currentCoordinates);
        if($isInHotspot){
            return;
        }

        $closeCatches = $this->getCatchesInProximity($currentCoordinates);

        $this->createHotspot($closeCatches, $userId, $currentCoordinates);
    }

    public function getHotspots($userId)
    {
        $sql = "select id, X(coordinates) as lat, Y(coordinates) as lon from Hotspot where userId = ?;";
        return $this->select($sql, [$userId]);
    }


    /**
     * For getting all the hotspots info
     * @param $hotspotId
     * @return \stdClass|null
     */
    public function getHotspotInfos($hotspotId)
    {
        $sql = "Select X(H.coordinates) as lon, Y(H.coordinates) as lat, COUNT(C.id) as catches, T.bites as nbBites, T.hooks as nbHooks
                    From Catch C Join Trip T On C.tripId = T.id Join Hotspot H On C.hotspotId = H.id
                        Where H.id = ? Group By T.id";
        return $this->select($sql, [$hotspotId]);
    }

    /**
     * Returns the avg wind speed for all the trips
     *
     * @param $hotspotId
     * @return \stdClass|null
     */
    public function getHotspotsWindAvg($hotspotId) {
        $sql = "Select X(H.coordinates) as lon, Y(H.coordinates) as lat, AVG(W.speed) as avgWindSpeed From Hotspot H Join Catch C on H.id = C.hotspotId JOIN Winds W On C.id = W.catchId Where H.id = ?";
        return $this->selectSingle($sql, [$hotspotId]);
    }

    /**
     * Returns all of the users hotspots with the last day visited
     *
     * @param $userId
     * @return \stdClass[]
     */
    public function getHotspotsInfoForPermissions($userId) {
        $sql = "Select H.id, X(H.coordinates) as lon, Y(H.coordinates) as lat, H.isShared ,MAX(C.time) as lastTime From Hotspot H Join Catch C on H.id = C.hotspotId Where H.userId = ? GROUP BY H.id, lon, lat";
        return $this->select($sql, [$userId]);
    }

    /**
     * For getting the list of avg pressure of each trip in the hotspot
     *
     * @param $hotspotId
     */
    public function getListOfPressures($hotspotId)
    {
        $sql = "Select T.dateTimeStart as tripDate, AVG(C.barometricPressure) as avgPressure From Hotspot H Join Catch C On H.id = C.hotspotId JOIN Trip T On C.tripId = T.id Where H.id = ? group by T.dateTimeStart";
        return $this->select($sql, [$hotspotId]);
    }

    /**
     * Returns the min and max wind speeds for each trip in the hotspot
     *
     * @param $hotspotId
     * @return \stdClass[]
     */
    public function getHotspotsWindsRange($hotspotId) {
        $sql = "Select  T.dateTimeStart, MIN(W.speed) as minSpeed, MAX(W.speed) as maxSpeed
                    From Catch C Join Trip T On C.tripId = T.id Join Hotspot H On C.hotspotId = H.id Join Winds W on C.id = W.catchId
                        Where H.id = ? GROUP BY T.id;";
        return $this->select($sql, [$hotspotId]);
    }

    /**
     * Changes the permission of the hotspot to its opposite
     *
     * @param $hotspotId
     * @return \stdClass|null
     */
    public function changePerm($hotspotId) {
        $sql = "Update Hotspot Set isShared = not isShared where id = ?";
        return $this->selectSingle($sql, [$hotspotId]);
    }

    /**
     * Returns the avg wind speed for each trip in the hotspot
     *
     * @param $hotspotId
     * @return \stdClass[]
     */
    public function getHotspotsWindsAvgByTrip($hotspotId) {
        $sql = "Select  T.dateTimeStart, AVG(W.speed) as avgSpeed
                    From Catch C Join Trip T On C.tripId = T.id Join Hotspot H On C.hotspotId = H.id Join Winds W on C.id = W.catchId
                        Where H.id = ? GROUP BY T.id;";
        return $this->select($sql, [$hotspotId]);
    }

    private function getCatchAloneView()
    {
        $sql = "select * from CatchAlone;";
        return $this->select($sql);
    }

    /**
     * If catch is in a hotspot, add it to the hotspot and return true else just return false
     *
     * @param $lastCatchId
     * @param $userId
     * @param $currentCoordinates
     * @return bool
     */
    private function insertCatchToExistingHotspot($lastCatchId, $userId, $currentCoordinates): bool
    {
        foreach (($this->getHotspots($userId)) as $hotspot) {
            if ($this->measureAccurately($currentCoordinates->lat, $currentCoordinates->lon, $hotspot->lat, $hotspot->lon) < self::HOTSPOT_RADIUS) {
                $this->addCatchToHotspot($hotspot->id, $lastCatchId);
                return true;
            }
        }
        return false;
    }

    private function getCatchesInProximity($currentCoordinates)
    {
        $closeCatches = array();
        foreach ($this->getCatchAloneView() as $catch) {
            if ($this->measureAccurately($currentCoordinates->lat, $currentCoordinates->lon, $catch->lat, $catch->lon) < self::HOTSPOT_RADIUS) {
                $closeCatches[] = $catch;
            }
        }

        return $closeCatches;
    }

    private function createHotspot($closeCatches, $userId, $currentCoordinates)
    {
        if(count($closeCatches) >= 2) {
            $hotspotId = $this->insertNewHotspot($userId, $currentCoordinates);
            foreach ($closeCatches as $closeCatch) {
                $this->addCatchToHotspot($hotspotId, $closeCatch->id);
            }
        }
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

    private function addCatchToHotspot($hotspotId, $catchId)
    {
        $sql = "update Catch set hotspotId = ? where id = ?";
        $this->query($sql, [$hotspotId, $catchId]);
    }

    private function insertNewHotspot($userId, $currentCoordinates): int
    {
        $sql = "insert into Hotspot values (default, ?, now(), false, point(?, ?));";
        $this->query($sql, [$userId, $currentCoordinates->lat, $currentCoordinates->lon]);
        return $this->getDatabase()->getLastInsertedId();
    }

    private function getCatchInfo($catchId)
    {
        $sql = "select X(C.coordinates) as lat, Y(C.coordinates) as lon, U.id as id
            from Catch C join Trip T on T.id = C.tripId join User U on U.id = T.userId where C.id = ?;";
        return $this->selectSingle($sql, [$catchId]);
    }
}