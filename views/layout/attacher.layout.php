<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.1/daisyui.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/cally"></script>

    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.min.css"
        rel="stylesheet" />
    <title>Document</title>
</head>

<body class="flex items-center h-screen bg-blue-50">
    <?php require_once ROOT_PATH . "/components/sidebar/sidebarAtt.php"; ?>
    <div class="w-full flex flex-col lg:ml-56 h-full">
        <?php require_once ROOT_PATH . "/components/header/headerAtt.php"; ?>
        <?= $content ?>
    </div>
    <script src="javascript/security.js"></script>
</body>

</html>