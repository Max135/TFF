<?php namespace Models\Brokers;

class CatchBroker extends Broker
{
    //Lat/lon still depends on a model of the earth. Typically, when people say lat/lon,
    // the WGS84 reference geoid is assumed, but you can have lat/lon with a different geoid, hence the SRID 4326 that combines these two.

    //SELECT X(coordinates),Y(coordinates) FROM table

    //https://webmasters.stackexchange.com/questions/76607/browser-shows-ip-address-instead-of-my-domain-name-when-using-google-cloud

    /**
     * Insert the catch into the database and checks for hotspots automatically
     *
     * @param $tripId
     * @param $temperature
     * @param $pressure
     * @param $humidity
     * @param $time
     * @param $lng
     * @param $lat
     * @param null $hotspotId
     * @return int
     */
    public function insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat, $hotspotId = null): int
    {
        $catchId = $this->insertCatch($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat, $hotspotId);
        (new HotspotBroker())->createNewHotspot($catchId);
        return $catchId;
    }

    /**
     * Inserts a new wind in the DB, wind param is a string that has the direction followed by the speed separated by a space
     * Example wind format : [SSO 10.4], units in km/h
     *
     * @param $catchId
     * @param $wind
     */
    public function insertWind($catchId, $wind)
    {
        $sql = "insert into Winds values (default, ?, ?, ?)";
        $this->query($sql, [$catchId, $this->getWindSpeed($wind), $this->getWindDirection($wind)]);
    }

    private function insertCatch($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat, $hotspotId = null): int
    {
        $sql = "insert into Catch values (default, ?, ?, ?, ?, ?, ?, point(?, ?));";
        $this->query($sql, [$tripId, $hotspotId, $temperature, $pressure, $humidity, $time, $lat, $lng]);
        return $this->getDatabase()->getLastInsertedId();
    }

    /**
     * Gets the first char of wind direction since DB field only takes 1 char
     *
     * @param $wind
     * @return false|string
     */
    private function getWindDirection($wind)
    {
        return substr($wind, 0, 1);
    }

    /**
     * Gets wind speed from wind string
     *
     * @param $wind
     * @return false|string
     */
    private function getWindSpeed($wind)
    {
        $index = strpos($wind, ' ') + 1;
        return floatval(substr($wind, $index, (strlen($wind) - $index)));
    }
}