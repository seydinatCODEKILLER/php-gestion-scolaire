
<?php

function isUserLoggedIn()
{
    if (!getDataFromSession("user")) {
        redirectURL("security", "connexion");
        exit();
    };
}

function isLogged()
{
    if (getDataFromSession("user")) {
        redirectUserByRole(getDataFromSession("user", "libelle"));
        exit;
    }
}

function credentialUser(string $email, string $password): array | null
{
    $sql = "";
    $params = [":email" => $email];
    $user = fetchResult($sql, $params, false);
    if ($user && $user['password'] === $password) {
        return $user;
    }
    return null;
}
