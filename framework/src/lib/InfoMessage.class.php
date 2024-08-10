<?php

class InfoMessage {
    private string $message;
    private InfoMessageType $type;

    public function __construct(string $message, InfoMessageType $type) {
        $this->message = $message;
        $this->type = $type;

        if(array_key_exists("infoMessages", $_SESSION)) {
            $_SESSION["infoMessages"][] = $this;
        } else {
            $_SESSION["infoMessages"] = [$this];
        }
    }

    /**
     * Checks whether there are messages to display
     * @return bool
     */
    public static function hasMessages(): bool {
        if(array_key_exists("infoMessages", $_SESSION)) {
            return sizeof($_SESSION["infoMessages"]) > 0;
        }

        return false;
    }

    /**
     * Returns all messages and removes them from the session
     * @return array
     */
    public static function getMessages(): array {
        if(array_key_exists("infoMessages", $_SESSION)) {
            $infoMessages = $_SESSION["infoMessages"];
            usort($infoMessages, ["InfoMessage", "compare"]);
            unset($_SESSION["infoMessages"]);

            return $infoMessages;
        }

        return [];
    }

    /**
     * Returns the message
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * Returns the infomessage type
     * @return InfoMessageType
     */
    public function getType(): InfoMessageType {
        return $this->type;
    }

    /**
     * Compares the importance of two infomessages
     * @param $a
     * @param $b
     * @return mixed
     */
    private static function compare($a, $b) {
        return $b->getType()->getCode() - $a->getType()->getCode();
    }
}
