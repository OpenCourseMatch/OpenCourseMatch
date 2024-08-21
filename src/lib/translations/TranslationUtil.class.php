<?php

class TranslationUtil {
    /**
     * Returns the users preferred locale
     * @return string
     */
    public static function getPreferredLocale(): string {
        $preferredLocales = Translator::getPreferredLocalesFromHeader();
        $existingLanguages = Translator::getAvailableLocales();

        foreach($preferredLocales as $preferredLocale) {
            $language = $preferredLocale["language"];
            $script = $preferredLocale["script"];
            $region = $preferredLocale["region"];
            $regex = $language;
            if($script) {
                $regex .= "_" . $script;
            } else {
                $regex .= "(_[a-zA-Z]+)?";
            }
            if($region) {
                $regex .= "_" . $region;
            } else {
                $regex .= "(_[a-zA-Z]{2})?";
            }

            foreach($existingLanguages as $existingLanguage) {
                if(preg_match("/^$regex$/", $existingLanguage)) {
                    return $existingLanguage;
                }
            }
        }

        // Fallback
        return "en_US";
    }
}
