<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 4/10/20
 * Time: 12:50 PM
 */

function checkJSON($headers)
{
    $check = false;

    if ($headers['content-type'] == 'application/json') $check = true;

    return $check;
}