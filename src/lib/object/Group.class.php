<?php

class Group extends GenericObject {
    public ?string $name = null;
    public ?int $clearance = null;

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getClearance(): ?int {
        return $this->clearance;
    }

    public function setClearance(?int $clearance): void {
        $this->clearance = $clearance;
    }
}
