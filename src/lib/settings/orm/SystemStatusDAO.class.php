<?php

namespace app\settings;

class SystemStatusDAO extends \struktal\ORM\GenericEntityDAO {
    public function get(string $key): ?string {
        $object = $this->getObject(["key" => $key]);
        if($object instanceof SystemStatus) {
            return $object->getValue();
        }

        return null;
    }

    public function set(string $key, string $value): void {
        $object = $this->getObject(["key" => $key]);
        if($object === null) {
            $object = new SystemStatus();
            $object->setKey($key);
        }
        $object->setValue($value);
        $this->save($object);
    }
}
