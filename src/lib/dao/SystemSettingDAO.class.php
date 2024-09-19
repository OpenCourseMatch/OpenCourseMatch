<?php

class SystemSettingDAO extends GenericObjectDAO {
    public function get(string $key): ?string {
        $object = $this->getObject(["key" => $key]);
        if($object instanceof SystemSetting) {
            return $object->getValue();
        }

        return null;
    }

    public function set(string $key, string $value): void {
        $object = $this->getObject(["key" => $key]);
        if(!$object instanceof SystemSetting) {
            $object = new SystemSetting();
            $object->setKey($key);
        }
        $object->setValue($value);
        $this->save($object);
    }

    public function setDefaults(bool $forced = false): void {
        foreach($this->defaultValues() as $key => $value) {
            if(!$forced && $this->get($key) !== null) {
                continue;
            }

            $this->set($key, $value["value"]);
        }
    }

    public function defaultValues(): array {
        return [
            "voteCount" => [
                "value" => "3",
                "name" => t("Vote count"),
                "description" => t("The number of courses that the participants have to choose"),
                "validation" => \validation\Validator::create([
                    \validation\MaxLength::create(512),
                    \validation\IsInteger::create(),
                    \validation\MinValue::create(1)->setErrorMessage(t("The participants have to choose at least one course."))
                ])
            ]
        ];
    }
}
