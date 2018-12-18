<?php

function page_image($value = null)
{
    if (empty($value)) {
        $value = config('blog.page_image');
    }
    if (!starts_with($value, 'http') && $value[0] !== '/') {
        $value = config('blog.uploads.webpath') . '/' . $value;
    }

    return $value;
}