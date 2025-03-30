<?php

define("PATH_VIEW_SECURITY", "/views/auth/");
$page = isset($_GET["page"]) ? $_GET["page"] : "connexion";

ob_start();
switch ($page) {
    case 'connexion':
        isLogged();
        clearFieldErrors();
        if (is_request_method("get")) {
            require_once ROOT_PATH . PATH_VIEW_SECURITY . "connexion.html.php";
        }
        if (is_request_method("post")) {
            if (validateDataLogin($_POST)) {
                $email = $_POST["email"];
                $password = $_POST["password"];
                $user = credentialUser($email, $password);
                if ($user) {
                    saveToSession("user", $user);
                    setSuccess("Connexion reussit");
                    redirectUserByRole($user["libelle"]);
                } else {
                    setFieldError('credentials', 'Email ou mot de passe incorrect.');
                }
            }
            require_once ROOT_PATH . PATH_VIEW_SECURITY . "connexion.html.php";
        }
        break;
    case 'deconnexion':
        session_unset();
        session_destroy();
        redirectURL("security", "connexion");
        break;
    default;
        require_once ROOT_PATH . "/views/error/view.error.php";
        break;
}
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/security.layout.php";
