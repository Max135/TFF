<?php namespace Models\Brokers;

use Zephyrus\Security\Cryptography;

class UserBroker extends Broker
{
    public function insert(string $email, string $password, string $username): int
    {
        $sql = "insert into User values (default, ?, ?, ?, null);";
        $this->query($sql, [$email, Cryptography::hashPassword($password), $username]);

        return $this->getDatabase()->getLastInsertedId();
    }

    public function findById(int $id)
    {
        $sql = "select * from User where id = ?;";
        return $this->selectSingle($sql, [$id]);
    }

    public function validCredentials(string $email, string $password): bool
    {
        $sql = "select password from User where email = ?";
        $hashedPassword = $this->selectSingle($sql, [$email]);
        if ($hashedPassword != null) {
            return Cryptography::verifyHashedPassword($password, $hashedPassword);
        }
        return false;
    }
}