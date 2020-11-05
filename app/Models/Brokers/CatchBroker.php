<?php namespace Models\Brokers;

class CatchBroker extends Broker
{
    public function insert($tripId, $temperature, $pressure, $humidity, $time, $lng, $lat, $hotspotId = null)
    {
        $sql = "insert into Catch (id, tripId, hotspotId, temperature, barometricPressure, humidity, time, coordinates) values (default, ?, ?, ?, ?, ?, ?, point(?, ?));";
        $this->query($sql, [$tripId, $hotspotId, $temperature, $pressure, $humidity, $time, $lng, $lat]);
    }
}