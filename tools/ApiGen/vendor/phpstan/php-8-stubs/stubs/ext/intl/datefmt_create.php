<?php 

/* dateformat */
/** @param IntlTimeZone|DateTimeZone|string|null $timezone */
#[\Until('8.1')]
function datefmt_create(?string $locale, int $dateType, int $timeType, $timezone = null, \IntlCalendar|int|null $calendar = null, ?string $pattern = null) : ?\IntlDateFormatter
{
}
/* dateformat */
/** @param IntlTimeZone|DateTimeZone|string|null $timezone */
#[\Since('8.1')]
function datefmt_create(?string $locale, int $dateType = IntlDateFormatter::FULL, int $timeType = IntlDateFormatter::FULL, $timezone = null, \IntlCalendar|int|null $calendar = null, ?string $pattern = null) : ?\IntlDateFormatter
{
}