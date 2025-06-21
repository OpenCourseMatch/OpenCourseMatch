<?php

enum InfoMessageType {
    case INFO;
    case WARNING;
    case ERROR;
    case SUCCESS;

    /**
     * Returns the infomessage code
     * @return int
     */
    function getCode(): int {
        return match($this) {
            self::INFO => 0,
            self::WARNING => 1,
            self::ERROR => 2,
            self::SUCCESS => 3
        };
    }

    /**
     * Returns the formatted infomessage type
     * @return string
     */
    function getFormatted(): string {
        return match($this) {
            self::INFO => "info",
            self::WARNING => "warning",
            self::ERROR => "error",
            self::SUCCESS => "success"
        };
    }
}
