<?php namespace Models\Brokers;

class CatchBroker extends Broker
{
    //Lat/lon still depends on a model of the earth. Typically, when people say lat/lon,
    // the WGS84 reference geoid is assumed, but you can have lat/lon with a different geoid, hence the SRID 4326 that combines these two.

    //SELECT X(coordinates),Y(coordinates) FROM table

    //https://webmasters.stackexchange.com/questions/76607/browser-shows-ip-address-instead-of-my-domain-name-when-using-google-cloud

    public function insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat, $hotspotId = null): int
    {
        $sql = "insert into Catch values (default, ?, ?, ?, ?, ?, ?, point(?, ?));";
        $this->query($sql, [$tripId, $hotspotId, $temperature, $pressure, $humidity, $time, $lng, $lat]);
        return $this->getDatabase()->getLastInsertedId();
    }
}