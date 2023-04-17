<?php

namespace app\core;

use PDO;

abstract class DbModel extends Model
{
    abstract public function tableName() : string;
    abstract public function attributes() : array;
    abstract public function attributesToUpdate() : array;

    /**
     * Método para crear nuevos registros en una tabla de datos
     * @return bool|mixed
     */
    public function save()
    {
        try {
            $tablename = $this->tableName();
            $attributes = $this->attributes();
            $params = array_map(fn($attr) => ":$attr", $attributes);

            $statement = self::prepare("INSERT INTO $tablename (". implode(',', $attributes) .") VALUES (". implode(',', $params) .")");

            foreach ($attributes as $attribute)
            {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }

            return $statement->execute();
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public function update($id)
    {
        try {
            $tablename = $this->tableName();
            $attributes = $this->attributesToUpdate();
            $params = array_map(fn($attr) => "$attr = :$attr", $attributes);
            $query = "UPDATE $tablename SET ". implode(', ', $params) ." WHERE id = :id";
            $statement = self::prepare($query);
            $statement->bindParam(':id', $id);
            foreach ($attributes as $key => $value) {
                if ($value !== NULL)
                    $statement->bindParam(':'.$value, $this->$value);
            }
            return $statement->execute();
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public function getAll()
    {
        try {
            $tablename = $this->tableName();

            $statement = self::prepare("SELECT * FROM $tablename WHERE deleted_at IS NULL");

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public function findById($id)
    {
        try {
            $tablename = $this->tableName();

            $statement = self::prepare("SELECT * FROM $tablename WHERE id = '$id'");

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public function getTotalData($query)
    {
        try {
            $statement = self::prepare($query);

            $statement->execute();

            return $statement->rowCount();
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public function customQuery($query)
    {
        try {
            $statement = self::prepare($query);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public function customUpdate($query)
    {
        try {
            $statement = self::prepare($query);

            $statement->execute();

            return $statement->rowCount();
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public static function validateCsrf($csrf, $id)
    {
        try {
            $statement = self::prepare("SELECT usuarios.id FROM usuarios WHERE usuarios.csrf = '$csrf' AND  usuarios.id = $id");

            $statement->execute();

            return $statement->rowCount();
        } catch (\PDOException $e)
        {
            return $e->errorInfo[2];
        }
    }

    public static function prepare($sql)
    {
        return Application::$app->database->pdo->prepare($sql);
    }
}