<?php
    session_start();
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
            <div class="row text-light fs-3 ms-3">Информация</div>
            <div class="row bg-light">
                <div class="col-3 fs-4">
                    <?php
                        $database = mysqli_connect('localhost', 'root', '', 'creator');

                        if (mysqli_connect_errno())
                        {
                            echo '<div class="alert alert-danger" role="alert">
                            <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
                            </div>';
                            exit();
                        }

                        $id = $_GET['id'];
                        $result = mysqli_query($database, "SELECT * FROM `ideas` WHERE `ideaid` = $id");
                        $row = mysqli_fetch_row($result);
                    ?>
                    <p class="fw-bold mt-3"><?php echo $row[1]; ?></p>
                    <p><b>Автор:</b><br><?php $author = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `users` WHERE `userid` = $row[4]")); echo $author[2]; ?></p>
                    <p><b>Категория:</b><br><?php $category = mysqli_fetch_row(mysqli_query($database, "SELECT `title` FROM `categories` WHERE `categoryid` = $row[2]")); echo $category[0]; ?></p>
                    <p><b>Степень проработанности:</b><br><?php $stage = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `stages` WHERE `stageid` = $row[7]")); echo $stage[1]; ?></p>
                    <?php
                        if (isset($_SESSION['userid']))
                        {
                            echo '<p><i><a href="/pdf.php?idea='.$id.'" class="link-primary">скачать pdf</a></i></p>';
                        }
                    ?>
                </div>
                <div class="col bg-white">
                    <div class="mt-3 mb-5">
                        <p class="fw-bold fs-4">Описание:</p>
                        <p class="fs-5"><?php echo $row[3]; ?></p>
                    </div>
                    <div class="mb-3">
                        <p class="fw-bold fs-4">Вид отчислений:</p>
                        <?php
                            $fee = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `fees` WHERE `feeid` = $row[5]"));
                            if ($fee[0] == 1) { $fee_type = ' руб.'; }
                            else { $fee_type = '%'; }
                            echo '<p class="fs-5">'.$fee[1].' в размере '.$row[6], $fee_type.'</p>';
                        ?>
                    </div>
                    <?php
                        if (isset($_SESSION['userid']))
                        {
                            echo '<div class="mb-3">';
                            echo '<a class="btn btn-outline-dark" href="mailto:'.$author[4].'" role="button">Связаться с автором (e-mail)</a>';
                            echo '</div>';
                        }
                    ?>
                    <?php
                        if ((isset($_SESSION['userid']) && $row[4] == $_SESSION['userid']) || (isset($_SESSION['status']) && $_SESSION['status'] == 1))
                        {
                            echo '<div class="mb-3">';
                            echo '<form method="POST" action="/ideas.php">';
                            echo '<input type="hidden" name="idIdea" value="'.$_GET['id'].'">';
                            echo '<button type="submit" class="btn btn-outline-danger">Удалить</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                    ?>
                </div>       
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>