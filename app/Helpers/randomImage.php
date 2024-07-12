<?php
function getRandomImageUrl()
{
    $seed = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 10);
    $url = "https://picsum.photos/seed/$seed/1280/720";
    return $url;
}
