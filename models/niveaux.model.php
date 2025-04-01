<?php

function getAllNiveaux()
{
    $sql = "SELECT * FROM niveaux WHERE state = 'disponible'";
    return fetchResult($sql);
}
