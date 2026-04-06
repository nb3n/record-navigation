<?php

if (! function_exists('getCdnUrl')) {
    /**
     * Append the given path to the base URL.
     *
     * @param  string  $path
     * @return string
     */
    function getCdnUrl($path)
    {
        $baseUrl = env('CLOUDFLARE_R2_URL');

        // Check if the path is empty or null and return base URL as fallback
        if (empty($path)) {
            return $baseUrl;
        }

        // Ensure the path doesn't start with a '/'
        if ($path[0] === '/') {
            $path = substr($path, 1);
        }

        // Return the full URL by concatenating the base URL with the given path
        return $baseUrl.'/'.$path;
    }
}