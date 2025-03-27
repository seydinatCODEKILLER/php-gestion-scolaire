<?php

function validateDataLogin($data)
{
    if (empty($data["email"])) {
        setFieldError('email', "l'adresse mail est requise");
    }
    if (empty($data["password"])) {
        setFieldError('password', "le password est requise");
    }

    return empty(getFieldErrors());
}
