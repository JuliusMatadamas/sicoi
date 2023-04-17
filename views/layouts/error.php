<!doctype html>
<html lang="<?php echo LANG; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo NAME_APP; ?></title>
    <link rel="stylesheet" href="<?php echo ENTORNO; ?>/public/css/main.css">
    {{css}}
</head>
<body>
{{content}}
<!-- JS -->
<script src="<?php echo ENTORNO; ?>/public/js/main.js"></script>
{{js}}
</body>
</html>