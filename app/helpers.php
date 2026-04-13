<?php

function fileUrl($path)
{
    if (!$path) {
        return null;
    }

    return asset((app()->environment('local') ? '' : 'public/') . $path);
}