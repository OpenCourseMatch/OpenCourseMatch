<?php

class SEO {
    private static ?string $description = null;
    private static bool $unlisted = false;

    /**
     * Sets the website description
     * @param string $description
     * @return void
     */
    public static function setDescription(string $description): void {
        self::$description = $description;
    }

    /**
     * Returns the website description
     * @return string
     */
    public static function getDescription(): string {
        if(self::$description !== null) {
            return self::$description;
        } else {
            return Config::$SEO_SETTINGS["SEO_DEFAULT_DESCRIPTION"];
        }
    }

    /**
     * Returns the website's keywords
     * @return string
     */
    public static function getKeywords(): string {
        return implode(", ", Config::$SEO_SETTINGS["SEO_KEYWORDS"]);
    }

    /**
     * Returns the link to the preview image
     * @return string
     */
    public static function getImagePreview(): string {
        return Config::$SEO_SETTINGS["SEO_IMAGE_PREVIEW"];
    }

    /**
     * Returns the OpenGraph site name
     * @return string|null
     */
    public static function getOgSiteName(): ?string {
        return Config::$SEO_SETTINGS["SEO_OPENGRAPH"]["OPENGRAPH_SITE_NAME"];
    }

    /**
     * Returns the Twitter site name
     * @return string|null
     */
    public static function getTwitterSite(): ?string {
        return Config::$SEO_SETTINGS["SEO_TWITTER"]["TWITTER_SITE"];
    }

    /**
     * Returns the Twitter creator
     * @return string|null
     */
    public static function getTwitterCreator(): ?string {
        return Config::$SEO_SETTINGS["SEO_TWITTER"]["TWITTER_CREATOR"];
    }

    /**
     * Returns the value for the robots meta tag
     * @return string
     */
    public static function getRobots(): string {
        if(self::$unlisted) {
            return implode(", ", ["noindex, nofollow"]);
        } else {
            return implode(", ", Config::$SEO_SETTINGS["SEO_ROBOTS"]);
        }
    }

    /**
     * Returns the value for the revisit-after meta tag
     * @return string
     */
    public static function getRevisitAfter(): string {
        return Config::$SEO_SETTINGS["SEO_REVISIT"];
    }

    /**
     * Hides the page from search engines
     * @return void
     */
    public static function setUnlisted(): void {
        self::$unlisted = true;
    }
}
