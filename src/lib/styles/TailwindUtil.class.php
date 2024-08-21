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
    public static string $inputLabel = "text-sm font-bold data-[required]:after:content-['*'] data-[required]:after:text-primary";
    public static string $input = "w-full px-2 py-1 bg-background border border-gray outline-primary rounded placeholder:text-font-light";
    public static string $checkbox = "px-2 py-1 bg-background border border-gray outline-primary rounded placeholder:text-font-light";
    public static string $radio = "px-2 py-1 bg-background border border-gray outline-primary rounded placeholder:text-font-light";
    public static string $textarea = "w-full px-2 py-1 bg-background border border-gray outline-primary rounded placeholder:text-font-light";
    public static string $select = "w-full px-2 py-1 bg-background border border-gray outline-primary rounded placeholder:text-font-light";
    public static function button(bool $flat = false, string $theme = "primary"): string {
        $class = "inline-flex justify-center items-center gap-1 border rounded transition-all ";
        if($flat) {
            $class .= "px-2 py-1 ";
        } else {
            $class .= "px-4 py-2 ";
        }

        // Apply colors
        $class .= "text-{$theme}-font bg-{$theme} border-{$theme} outline-{$theme} hover:bg-{$theme}-effect hover:border-{$theme}-effect ";

        // Apply fallback for disabled buttons
        $class .= "disabled:opacity-75 disabled:hover:bg-{$theme} disabled:hover:border-{$theme} ";

        return $class;
    }
}
