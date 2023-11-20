<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pedimos Fácil</title>
        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            @font-face { font-family: "gotham"; src: url("<?php echo $_POST['base_url']; ?>templates/513/qr/GothamRounded-BoldItalic.otf");}
            * {font-family: "gotham"; color: #ffffff;}
            .principal { background-image: url(<?php echo $_POST['base_url']; ?>templates/513/qr/images/fondo-qr.png); max-height: 800px; background-repeat: no-repeat; background-position: center; margin-top: 10px;}
            .titulo { text-align: center; margin-top: 20px;}
            .titulo h1 {font-size: 40px; margin-top: 40px; margin-left: 40px; margin-right: 40px;}
            .qr-image {text-align: center;}
            .qr-image img {margin-top: 30px; width: 300px;}
            .pasos {text-align: center; margin-top: 50px;}
            .pasos h2 {margin-top: 20px; background-color: #369644; border-radius: 20px; display: inline-block; padding-right: 10px;}
            .pasos h2 span {background-color: #4c2f84; border-radius: 50%; width: 50px;}
            .url {text-align: center; margin-top:20px; margin-bottom:20px;}
            .url a {color: #4c2f84; font-size: 20px; text-decoration: none; word-break: break-all;}
            @media only screen and (max-width: 400px){ .titulo h1 {margin-left: auto; margin-right: auto;}}
            @media only screen and (max-width: 350px){ .pasos h2 {font-size: 21px;}}
            @media print { * {-webkit-print-color-adjust: exact; page-break-inside: avoid;} .logo{page-break-after:; } .container{page-break-inside: avoid;}}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                </div>
                <div class="col-lg-6 principal">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="titulo">
                                <h1>Consult&aacute; ac&aacute;<br>nuestro men&uacute;</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qr-image">
                            <img src="<?php echo $_POST['base_url']; ?>tienda/qr/index/<?php echo $_POST['id']; ?>/menu/" alt="QR Pedimos Facil" title="Escaneá este código">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pasos">
                                <h2><span>&nbsp;1&nbsp;</span>  Abr&iacute; tu c&aacute;mara o lector</h2><br>
                                <h2><span>&nbsp;2&nbsp;</span>  Escane&aacute; el c&oacute;digo QR</h2><br>
                                <h2><span>&nbsp;3&nbsp;</span>  Eleg&iacute; tu comida</h2>
                            </div>
                            <div class="url">
                                <a href="<?php echo $_POST['url'] . $_POST['titulo']; ?>" target="blank"><?php echo $_POST['url'] . $_POST['titulo']; ?>?tipo=menu</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                </div>
                <div class="col-lg-6">
                    <div class="logo mx-auto">
                        <a href="<?php echo $_POST['base_url']; ?>" class="mx-auto d-block"><img src="<?php echo $_POST['base_url']; ?>templates/513/qr/images/logo-pedimos-facil.png" alt="logo pedimos facil" class="mx-auto d-block img-fluid" style="padding: 20px; width: 250px;"></a>
                    </div>
                </div>
                <div class="col-lg-3">
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>