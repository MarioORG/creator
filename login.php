<?php
    session_start();

    if (isset($_GET['logout'])) {
        session_destroy();
        header("location:login.php");
    }

    if (isset($_SESSION['userid']))
    {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $status = $_SESSION['status'];
    }
    else 
    {
        $login = $_POST['loginReg'];
        $password = $_POST['passwordReg'];
    }

    if ($_POST && isset($_POST['loginReg']) && isset($_POST['passwordReg']))
    {
        $database = mysqli_connect('localhost', 'root', '', 'creator');

        if (mysqli_connect_errno())
        {
            echo '<div class="alert alert-danger" role="alert">
            <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
            </div>';
            exit();
        }

        $login = $_POST['loginReg'];
        $password = $_POST['passwordReg'];

        $check_login = mysqli_query($database, "SELECT * FROM `users` WHERE `Login` = '$login' AND `Password` = sha1('$password')");
        if (mysqli_num_rows($check_login) > 0)
        {
            $user_info = mysqli_fetch_row($check_login);
            if (!isset($_SESSION['userid']))
            {
                $_SESSION['userid'] = $user_info[0];
                $_SESSION['login'] = $login;
                $_SESSION['password'] = $password;
                $_SESSION['status'] = $user_info[6];
            }

            echo '<div class="alert alert-success" role="alert">
            <strong>Вы успешно авторизовались в системе!</strong>
            </div>';

            header("location:ideas.php");
        }
        else
        {
            echo '<div class="alert alert-danger" role="alert">
            <strong>Ошибка! Неправильный логин или пароль.</strong>
            </div>';
        }
        mysqli_close($database);
    }
?>

<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        
        <title>Авторизация</title>
    </head>
    <body class="font-monospace">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fs-3 fw-bold" href="/">КРЕАТОР</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Меню">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse fs-5" id="navbarContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/ideas.php">Список идей</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/suggest.php">Предложить идею</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                    <?php
                        if (!isset($_SESSION['userid']))
                        {
                            echo '<li class="nav-item">
                                <a class="nav-link" href="/registration.php">Регистрация</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/login.php">Войти</a>
                            </li>';
                        }
                        else
                        {
                            echo '<li class="nav-item">
                                <a class="nav-link" href="/account.php">'.$_SESSION['login'].'</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/login.php?logout=true">Выход</a>
                            </li>';
                        }
                    ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- контейнер в столбец -->
        <div class="container mt-5 border border-dark rounded-top bg-dark w-25">
            <div class="row text-light fs-3 ms-3">Авторизация</div>
            <div class="row bg-light">
                <div class="col-1"></div>
                <div class="col bg-white">
                    <?php
                        if (isset($_SESSION['userid']))
                        {
                            echo '<div class="alert alert-info mt-3" role="alert">
                            <strong>Вы уже авторизованы!</strong>
                            </div></div></div></div>';
                            exit();
                        }
                    ?>
                    <form method="POST" action="/login.php">
                        <div class="mt-3 mb-3 fs-4 fw-bold">
                            <label for="loginReg" class="form-label">Логин</label>
                            <input type="text" class="form-control" id="loginReg" name="loginReg" maxlength="20" required>
                        </div>
                        <div class="mb-3 fs-4 fw-bold">
                            <label for="passwordReg" class="form-label">Пароль</label>
                            <input type="password" class="form-control" id="passwordReg" name="passwordReg" maxlength="20" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-outline-dark">Войти</button>
                            <!--<a href="#" class="link-dark">Забыли пароль?</a>-->
                        </div>
                    </form>
                </div>       
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>