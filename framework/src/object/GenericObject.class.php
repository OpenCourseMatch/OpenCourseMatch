<?php

class GenericObject {
    private static array $dao = [];

    public ?int $id;
    public DateTime $created;
    public DateTime $updated;

    public function __construct() {
        $this->id = null;
        $this->created = new DateTime();
        $this->updated = new DateTime();
    }

    /**
     * Returns the data access object for this class
     * @return GenericObjectDAO
     */
    public static function dao(): GenericObjectDAO {
        if(!(array_key_exists(get_called_class(), self::$dao))) {
            if(class_exists(get_called_class() . "DAO")) {
                $daoClassName = get_called_class() . "DAO";
                self::$dao[get_called_class()] = new $daoClassName(get_called_class());
            } else {
                Logger::getLogger("GenericObject")->error("DAO for Class " . get_called_class() . " requested but not found");
            }
        }

        return self::$dao[get_called_class()];
    }

    /**
     * Imports data from an array to the object
     * @param array $data
     * @return void
     */
    public function fromArray(array $data): void {
        $classProperties = get_object_vars($this);
        foreach($classProperties as $property => $value) {
            if(array_key_exists($property, $data)) {
                if($this->$property instanceof DateTime) {
                    $this->$property = DateTime::createFromFormat("Y-m-d H:i:s", $data[$property]);
                } else {
                    $this->$property = $data[$property];
                }
            } else {
                Logger::getLogger("GenericObject")->error("Critical: Property \"{$property}\" does not exist in Data Array");
            }
        }
    }

    /**
     * Exports the object's data to an array
     * @return array
     */
    public function toArray(): array {
        $classProperties = get_object_vars($this);
        $data = [];
        foreach($classProperties as $property => $value) {
            $data[$property] = $this->$property;
        }

        return $data;
    }

    /**
     * Returns the object's ID
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Sets the object's ID
     * @param int $id
     */
    private function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * Returns the object's creation date
     * @return DateTime
     */
    public function getCreated(): DateTime {
        return $this->created;
    }

    /**
     * Sets the object's creation date
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void {
        $this->created = $created;
    }

    /**
     * Returns the object's last update date
     * @return DateTime
     */
    public function getUpdated(): DateTime {
        return $this->updated;
    }

    /**
     * Sets the object's last update date
     * @param DateTime $updated
     */
    public function setUpdated(DateTime $updated): void {
        $this->updated = $updated;
    }
}
