<?php

class SystemSetting extends GenericObject {
    public ?string $key = null;
    public ?string $value = null;

    public function getKey(): ?string {
        return $this->key;
    }

    public function setKey(?string $key): void {
        $this->key = $key;
    }

    public function getValue(): ?string {
        return $this->value;
    }

    public function setValue(?string $value): void {
        $this->value = $value;
    }
}
