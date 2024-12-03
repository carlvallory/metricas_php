<?php

    require_once('config.php');

    if(isLogin()) {
        header("Location: ".$base_url."/get-excel");
        exit();
    }

    $error = null;

    if (isset($_POST['email']) && isset($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $login = userLogin($email, $password);

        if ($login !== null) {

                $_SESSION['tnm_user_id'] = $login['user_id'];
                $_SESSION['tnm_user_code'] = $login['user_code'];

                setcookie("tnm_user_id", $login['user_id'], time()+(12 * 30 * 24 * 60 * 60));
                setcookie("tnm_user_code", $login['user_code'], time()+(12 * 30 * 24 * 60 * 60));

                header("Location: ".$base_url."/get-excel");
                exit();

        }else{

            $error = "Usuario o contraseña inválida.";

        }
    }

?><!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Talentos | Nación Media</title>
    <link rel="icon" href="<?php echo $base_url ?>/assets/img/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $base_url ?>/assets/css/style.css" type="text/css" media="all" />
  </head>
  <body>

    <table class="form-signin-t">
        <td>
            <main class="form-signin">
              <form action="" method="post">
                <img class="mb-5" src="<?php echo $base_url ?>/assets/img/logo-nacion-media.png" alt="" />

                <?php if($error !== null) { ?>
                <div class="alert alert-danger" role="alert"><?php echo $error ?></div>
                <?php } ?>

                <div class="form-floating mb-1">
                  <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email" required>
                  <label for="floatingInput">Email</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Contraseña" required>
                  <label for="floatingPassword">Contraseña</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Acceder</button>
              </form>
            </main>
        </td>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html><?php 
$mysqli->close(); ?>