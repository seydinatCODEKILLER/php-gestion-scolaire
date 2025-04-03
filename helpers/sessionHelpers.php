<?php

function startSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function setFieldError(string $field, string $message)
{
    $_SESSION['errors'][$field] = $message;
}

function getFieldError(string $field)
{
    return $_SESSION['errors'][$field] ?? null;
}

function setSuccess(string $message)
{
    $_SESSION['success'] = $message;
}

function getSuccess()
{
    $message = $_SESSION["success"] ?? null;
    clearSuccess();
    return $message;
}

function clearSuccess()
{
    unset($_SESSION["success"]);
}

function clearFieldErrors()
{
    unset($_SESSION['errors']);
}

function getFieldErrors()
{
    return isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
}

function saveToSession(string $key, $data)
{
    $_SESSION[$key] = $data;
}

function removeToSession(string $key)
{
    unset($_SESSION[$key]);
}

function getDataFromSession(string $key, string $value = "")
{
    if (!empty($value)) {
        return $_SESSION[$key][$value] ?? null;
    }
    return $_SESSION[$key] ?? null;
}
