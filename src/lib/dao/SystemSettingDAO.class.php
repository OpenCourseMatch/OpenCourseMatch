<?php

class SystemSettingDAO extends GenericObjectDAO {
    private static array $cache = [];

    public function get(string $key): ?string {
        // Check cache first
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        // If not in cache, fetch from database
        $object = $this->getObject(["key" => $key]);
        if($object instanceof SystemSetting) {
            $value = $object->getValue();
            // Store in cache for future requests
            self::$cache[$key] = $value;
            return $value;
        }

        // Cache null result to avoid repeated database queries
        self::$cache[$key] = null;
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
        
        // Update cache with new value
        self::$cache[$key] = $value;
    }

    /**
     * Clears the entire cache or a specific key from the cache
     * @param string|null $key If provided, only clears the specific key
     * @return void
     */
    public function clearCache(?string $key = null): void {
        if ($key !== null) {
            unset(self::$cache[$key]);
        } else {
            self::$cache = [];
        }
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
            "choiceCount" => [
                "value" => "3",
                "name" => t("Choice count"),
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
