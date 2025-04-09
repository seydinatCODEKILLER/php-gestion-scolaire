<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.1/daisyui.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="https://unpkg.com/cally"></script>

    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.min.css"
        rel="stylesheet" />
    <title>Document</title>
</head>

<body class="flex items-center h-screen">
    <?php require_once ROOT_PATH . "/components/sidebar/sidebar.php"; ?>
    <div class="w-full flex flex-col lg:ml-56 h-full">
        <?php if ($controllers === "responsable"): ?>
            <?php require_once ROOT_PATH . "/components/header/header.php"; ?>
        <?php elseif ($controllers === "professeur"): ?>
            <?php require_once ROOT_PATH . "/components/header/headerProf.php"; ?>
        <?php elseif ($controllers === "attacher"): ?>
            <?php require_once ROOT_PATH . "/components/header/headerAtt.php"; ?>
        <?php endif; ?>
        <?= $content ?>
    </div>
    <script src="javascript/security.js"></script>
</body>

</html>