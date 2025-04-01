<?php

function getAllFileres()
{
    $sql = "SELECT * FROM filieres WHERE state = 'disponible'";
    return fetchResult($sql);
}
