<?php

if (!defined('_INCODE')) die('Access Denied...');

/*Write functions to handle session*/

function setSession($key, $value): bool
{
    if (!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    }

    return false;
}

function getSession($key = '')
{
    if (empty($key)) {
        return $_SESSION;
    } else {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    return false;
}

// Unset session variables
function removeSession($key = ''): bool
{
    if (empty($key)) {
        session_unset();
        return true;
    } else {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
    }

    return false;
}

// Terminate the session
function destroySession(): void
{
    // Unset all the session variables.
    $_SESSION = array();

    // Delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
}

// Set flash data
function setFlashData($key, $value): bool
{
    $key = 'flash_' . $key;
    return setSession($key, $value);
}

// Get flash data
function getFlashData($key)
{
    $key = 'flash_' . $key;
    $data = getSession($key);
    removeSession($key);

    return $data;
}
