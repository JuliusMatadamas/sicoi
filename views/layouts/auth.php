<!doctype html>
<html lang="<?php echo LANG; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo NAME_APP; ?></title>
    <link rel="stylesheet" href="<?php echo ENTORNO; ?>/public/css/main.css">
    <link rel="stylesheet" href="<?php echo ENTORNO; ?>/public/css/animate.min.css">
    {{css}}
</head>
<body>
{{content}}

<section id="modal-loading" class="modal">
    <div class="card">
        <div class="card-body text-center">
            <p>Espere mientras se realiza el proceso.</p>
            <br>
            <img src="<?php echo ENTORNO; ?>/public/img/settings.gif" alt="">
            <br>
            <small>Si el proceso tarda, refresque la página e intente de nuevo.</small>
        </div>
    </div>
</section>

<section id="modal-error" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-danger">¡Ocurrió un error!</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-error_message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-error_btn" class="col-12 text-end">
                    <button class="btn btn-dark w-50" onclick="ocultarModal(this)">Enterado</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="modal-login" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-success">¡Haz iniciado sesión!</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-login_message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-login_btn" class="col-12 text-end">
                    <button class="btn btn-dark w-50" onclick="ocultarModal(this)">Enterado</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="modal-info" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-success">Proceso realizado</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-info_message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-info_btn" class="col-12 text-end">
                    <button class="btn btn-dark w-50" onclick="ocultarModal(this)">Enterado</button>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- JS -->
<script src="<?php echo ENTORNO; ?>/public/js/main.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB"></script>
{{js}}
</body>
</html>