<?php

class TailwindUtil {
    // **********
    // Inputs
    // **********
    public static function inputGroup(bool $inline = false): string {
        $class = "w-full";
        if($inline) {
            $class .= " flex items-center gap-1";
        }
        return $class;
    }
    public static string $inputLabel = "text-sm font-bold data-required:after:content-['*'] data-required:after:text-primary-500";
    public static string $input = "w-full px-2 py-1 bg-transparent border border-surface-500 outline-primary-500 rounded placeholder:text-surface-500";
    public static string $checkbox = "px-2 py-1 bg-transparent border border-surface-500 outline-primary-500 rounded placeholder:text-surface-500";
    public static string $radio = "px-2 py-1 bg-transparent border border-surface-500 outline-primary-500 rounded placeholder:text-surface-500";
    public static string $textarea = "w-full px-2 py-1 bg-transparent border border-surface-500 outline-primary-500 rounded placeholder:text-surface-500";
    public static string $select = "w-full px-2 py-1 bg-transparent border border-surface-500 outline-primary-500 rounded placeholder:text-surface-500";
    public static function button(bool $flat = false, string $theme = "primary"): string {
        $class = "inline-flex justify-center items-center gap-1 border rounded transition-all ";
        if($flat) {
            $class .= "px-2 py-1 ";
        } else {
            $class .= "px-4 py-2 ";
        }

        // Apply colors
        $class .= "text-surface-100 bg-{$theme}-500 border-{$theme}-500 outline-{$theme}-500 hover:bg-{$theme}-600 hover:border-{$theme}-600 ";

        // Apply fallback for disabled buttons
        $class .= "disabled:bg-{$theme}-400 disabled:border-{$theme}-400";

        return $class;
    }
}
