<?php
    session_start();

    if ($_POST && isset($_POST['titleIdea']) && isset($_POST['categoryIdea']) && isset($_POST['descriptionIdea']) && isset($_POST['feeIdea']) && isset($_POST['amountIdea']))
    {
        $database = mysqli_connect('localhost', 'root', '', 'creator');

        if (mysqli_connect_errno())
        {
            echo '<div class="alert alert-danger" role="alert">
            <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
            </div>';
            exit();
        }

        $title = $_POST['titleIdea'];
        $category = $_POST['categoryIdea'];
        $description = $_POST['descriptionIdea'];
        $userid = $_SESSION['userid'];
        $fee = $_POST['feeIdea'];
        $amount = $_POST['amountIdea'];
        $stage = $_POST['stageIdea'];

        $check_title = mysqli_query($database, "SELECT * FROM `ideas` WHERE `Title` = '$title'");
        if (mysqli_num_rows($check_title) > 0)
        {
            echo '<div class="alert alert-warning" role="alert">
            <strong>Внимание! Идея с таким названием уже существует.</strong>
            </div>';
        }
        else
        {
            $result = mysqli_query($database, "INSERT INTO `ideas` VALUES (NULL, '$title', $category, '$description', $userid, $fee, $amount, $stage)");
            if ($result)
            {
                echo '<div class="alert alert-success" role="alert">
                <strong>Ваша идея успешно добавлена в список!</strong>
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
        
        <title>Предложить идею</title>
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
                            <a class="nav-link active" aria-current="page" href="/suggest.php">Предложить идею</a>
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
        <div class="container mt-5 mb-5 border border-dark rounded-top bg-dark">
            <div class="row text-light fs-3 ms-3">Предложить идею</div>
            <div class="row bg-light">
                <div class="col-3 fs-4">
                    <p class="mt-3">На данной странице Вы можете предложить свою идею для реализации.
                    Введите название, опишите задумку и выберите вид отчислений.</p>
                </div>
                <div class="col bg-white fs-4 fw-bold">
                    <?php
                        if (!isset($_SESSION['userid']))
                        {
                            echo '<div class="alert alert-warning mt-3" role="alert">
                            <strong>Чтобы предложить идею, пройдите авторизацию!</strong>
                            </div></div></div></div>';
                            exit();
                        }
                    ?>
                    <form method="POST" action="/suggest.php">
                        <div class="mt-3 mb-3">
                            <label for="titleIdea" class="form-label">Название</label>
                            <input type="text" class="form-control" id="titleIdea" name="titleIdea" maxlength="40" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoryIdea" class="form-label">Категория</label>
                            <select class="form-select" id="categoryIdea" name="categoryIdea">
                                <?php
                                    $database = mysqli_connect('localhost', 'root', '', 'creator');

                                    if (mysqli_connect_errno())
                                    {
                                        echo '<div class="alert alert-danger" role="alert">
                                        <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
                                        </div>';
                                        exit();
                                    }

                                    $result = mysqli_query($database, "SELECT * FROM `categories`");
                                    while ($row = mysqli_fetch_row($result))
                                    {
                                        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descriptionIdea" class="form-label">Описание</label>
                            <textarea type="text" class="form-control" id="descriptionIdea" name="descriptionIdea" maxlength="512" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="feeIdea" class="form-label">Вид отчислений</label>
                            <select class="form-select mb-3" id="feeIdea" name="feeIdea">
                                <?php
                                    $result = mysqli_query($database, "SELECT * FROM `fees`");
                                    while ($row = mysqli_fetch_row($result))
                                    {
                                        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                                    }
                                ?>
                            </select>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="amountIdea">в размере:</label>
                                </div>
                                <input type="text" class="form-control" id="amountIdea" name="amountIdea" maxlength="9" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stageIdea" class="form-label">Степень проработанности</label>
                            <select class="form-select mb-3" id="stageIdea" name="stageIdea">
                                <?php
                                    $result = mysqli_query($database, "SELECT * FROM `stages`");
                                    while ($row = mysqli_fetch_row($result))
                                    {
                                        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                                    }
                                    mysqli_close($database);
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-outline-dark">Предложить</button>
                        </div>
                    </form>
                </div>       
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>