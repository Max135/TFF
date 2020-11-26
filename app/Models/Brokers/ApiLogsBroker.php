<?php namespace Models\Brokers;

class ApiLogsBroker extends Broker
{
    public function insert($success, string $message)
    {
        $sql = "insert into ApiLogs values (?, ?)";
        $this->query($sql, [$success, $message]);
    }

    public function findAll()
    {
        $sql = "select * from ApiLogs";
        return $this->select($sql);
    }

    public function findAllBySuccess(bool $success)
    {
        $sql = "select * from ApiLogs where successState = ?";
        return $this->select($sql, [$success]);
    }

    public function deleteAll()
    {
        $sql = "delete from ApiLogs";
        $this->query($sql);
    }
}