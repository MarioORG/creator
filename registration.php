<?php
    session_start();

    if ($_POST && isset($_POST['fioReg']) && isset($_POST['loginReg']) && isset($_POST['emailReg']) && isset($_POST['passwordReg']) && isset($_POST['careerReg']))
    {
        $database = mysqli_connect('localhost', 'root', '', 'creator');

        if (mysqli_connect_errno())
        {
            echo '<div class="alert alert-danger" role="alert">
            <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
            </div>';
            exit();
        }

        $fio = $_POST['fioReg'];
        $login = $_POST['loginReg'];
        $email = $_POST['emailReg'];
        $password = $_POST['passwordReg'];
        $career = $_POST['careerReg'];

        $check_login = mysqli_query($database, "SELECT * FROM `users` WHERE `Login` = '$login'");
        $check_email = mysqli_query($database, "SELECT * FROM `users` WHERE `E-mail` = '$email'");
        if (mysqli_num_rows($check_login) > 0)
        {
            echo '<div class="alert alert-warning" role="alert">
            <strong>Внимание! Пользователь с данным логином уже зарегистрирован в системе.</strong>
            </div>';
        }
        else if (mysqli_num_rows($check_email) > 0)
        {
            echo '<div class="alert alert-warning" role="alert">
            <strong>Внимание! Пользователь с данной почтой уже зарегистрирован в системе.</strong>
            </div>';
        }
        else
        {
            $result = mysqli_query($database, "INSERT INTO `users` VALUES (NULL, '$fio', '$login', sha1('$password'), '$email', '$career', 0)");
            if ($result)
            {
                echo '<div class="alert alert-success" role="alert">
                <strong>Вы успешно зарегистрировались!</strong>
                </div>';
            }
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
        
        <title>Регистрация</title>
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
                                <a class="nav-link active" aria-current="page" href="/registration.php">Регистрация</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/login.php">Войти</a>
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
            <div class="row text-light fs-3 ms-3">Регистрация</div>
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
                    <form method="POST" action="/registration.php">
                        <div class="mt-3 mb-3 fs-4 fw-bold">
                            <label for="fioReg" class="form-label">ФИО</label>
                            <input type="text" class="form-control" id="fioReg" name="fioReg" maxlength="40" required>
                        </div>
                        <div class="mb-3 fs-4 fw-bold">
                            <label for="loginReg" class="form-label">Логин</label>
                            <input type="text" class="form-control" id="loginReg" name="loginReg" maxlength="20" required>
                        </div>
                        <div class="mb-3 fs-4 fw-bold">
                            <label for="emailReg" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="emailReg" name="emailReg" maxlength="40" required>
                        </div>
                        <div class="mb-3 fs-4 fw-bold">
                            <label for="passwordReg" class="form-label">Пароль</label>
                            <input type="text" class="form-control" id="passwordReg" name="passwordReg" maxlength="20" required>
                        </div>
                        <div class="mb-3 fs-4 fw-bold">
                            <label for="careerReg" class="form-label">Род деятельности</label>
                            <input type="text" class="form-control" id="careerReg" name="careerReg" maxlength="40" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-outline-dark">Зарегистрироваться</button>
                        </div>
                    </form>
                </div>       
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>