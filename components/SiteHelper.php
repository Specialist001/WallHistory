<?php

namespace app\components;

class SiteHelper
{
    /**
     * @return mixed
     */
    public static function getUserIP()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
    
    public static function hideIP($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // If IP is IPv4, hide the last two section
            $parts = explode('.', $ip);
            $maskedIP = $parts[0] . '.' . $parts[1] . '.*.*';
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // If IP is IPv6, hide the last four section
            $parts = explode(':', $ip);
            $maskedIP = implode(':', array_slice($parts, 0, -4)) . ':****:****:****:****';
        } else {
            // If IP is neither IPv4 nor IPv6, return the original IP
            $maskedIP = $ip;
        }

        return $maskedIP;
    }
}