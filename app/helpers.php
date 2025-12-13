<?php

if (! function_exists('formatMoney')) {
    /**
     * Format a number as Peruvian Soles (S/)
     *
     * @param  float|int  $amount
     * @param  int  $decimals
     * @return string
     */
    function formatMoney($amount, $decimals = 2)
    {
        return 'S/ '.number_format($amount, $decimals, '.', ',');
    }
}

if (! function_exists('formatDateTime')) {
    /**
     * Format a datetime in Lima timezone with 24-hour format
     *
     * @param  \Carbon\Carbon|string|null  $date
     * @param  string  $format  Default: 'd/m/Y H:i' (09/12/2025 14:30)
     * @return string
     */
    function formatDateTime($date, $format = 'd/m/Y H:i')
    {
        if (! $date) {
            return '-';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->timezone('America/Lima')->format($format);
    }
}

if (! function_exists('formatDate')) {
    /**
     * Format a date in Lima timezone
     *
     * @param  \Carbon\Carbon|string|null  $date
     * @param  string  $format  Default: 'd/m/Y' (09/12/2025)
     * @return string
     */
    function formatDate($date, $format = 'd/m/Y')
    {
        return formatDateTime($date, $format);
    }
}

if (! function_exists('formatTime')) {
    /**
     * Format time in Lima timezone with 24-hour format
     *
     * @param  \Carbon\Carbon|string|null  $date
     * @param  bool  $use12Hour  Use 12-hour format with AM/PM
     * @return string
     */
    function formatTime($date, $use12Hour = false)
    {
        $format = $use12Hour ? 'h:i A' : 'H:i';

        return formatDateTime($date, $format);
    }
}

if (! function_exists('formatDateTimeFull')) {
    /**
     * Format a datetime with full date and 12-hour time
     *
     * @param  \Carbon\Carbon|string|null  $date
     * @return string Example: 09/12/2025 02:30 PM
     */
    function formatDateTimeFull($date)
    {
        return formatDateTime($date, 'd/m/Y h:i A');
    }
}
