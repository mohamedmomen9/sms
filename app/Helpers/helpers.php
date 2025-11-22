<?php

function getImageApiUrl($path): ?string
{
    if (empty($path)) {
        return null;
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    if (request()->is('api/*')) {
        $websiteUrl = url('/storage');
        return $websiteUrl . '/' . $path;
    }
    return $path;
}
