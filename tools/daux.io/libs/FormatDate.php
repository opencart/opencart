<?php namespace Todaymade\Daux;

class FormatDate
{
    public static function format($config, $date)
    {
        $locale = $config->getLanguage();
        $datetype = \IntlDateFormatter::LONG;
        $timetype = \IntlDateFormatter::SHORT;
        $timezone = null;

        if (!extension_loaded('intl')) {
            $locale = 'en';
            $timezone = 'GMT';
        }

        $formatter = new \IntlDateFormatter($locale, $datetype, $timetype, $timezone);

        return $formatter->format($date);
    }
}
