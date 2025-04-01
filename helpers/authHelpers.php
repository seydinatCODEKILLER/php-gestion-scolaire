
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

function credentialUser(string $email, string $password): array|null
{
    $sql = "
    SELECT u.nom, u.prenom, u.avatar, r.libelle
    FROM utilisateurs u
    JOIN roles r ON r.id_role = u.id_role
    WHERE u.email = ? AND u.password = ?";
    $params = [$email, $password];
    $user = fetchResult($sql, $params, false);
    return $user ?: null;
}
