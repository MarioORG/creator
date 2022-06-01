<?php
    session_start();

    if($_POST && isset($_POST['idIdea']))
    {
        $id = $_POST['idIdea'];
        $database = mysqli_connect('localhost', 'root', '', 'creator');

        if (mysqli_connect_errno())
        {
            echo '<div class="alert alert-danger" role="alert">
            <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
            </div>';
            exit();
        }
        
        $result = mysqli_query($database, "DELETE FROM `ideas` WHERE `IdeaID` = $id");
        if ($result)
        {
            echo '<div class="alert alert-success" role="alert">
            <strong>Идея была удалена.</strong>
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
        
        <title>Список идей</title>
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
                            <a class="nav-link active" aria-current="page" href="/ideas.php">Список идей</a>
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
            <div class="row text-light fs-3 ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#searchIdea" aria-expanded="false" aria-controls="searchIdea">Поиск</div>
            <div class="row bg-light collapse" id="searchIdea">
                <div class="col fs-5 ms-3 pt-3">
                    <label for="stageIdea" class="form-label">Степень проработанности</label>
                    <select class="form-select mb-3" id="stageIdea" name="stageIdea" onchange="location = this.value;">
                        <option selected>выбрать из списка</option>
                        <option value="/ideas.php">показать все</option>
                        <?php
                            $database = mysqli_connect('localhost', 'root', '', 'creator');

                            if (mysqli_connect_errno())
                            {
                                echo '<div class="alert alert-danger" role="alert">
                                <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
                                </div>';
                                exit();
                            }

                            $result = mysqli_query($database, "SELECT * FROM `stages`");
                            while ($row = mysqli_fetch_row($result))
                            {
                                echo '<option value="/ideas.php?search='.$row[0].'">'.$row[1].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="container mt-5 mb-5 border border-dark rounded-top bg-dark">
            <div class="row text-light fs-3 ms-3">Список идей</div>
            <?php
                $search = $_GET['search'];
                if (!isset($search))
                {
                    $result = mysqli_query($database, "SELECT * FROM `ideas`");
                }
                else
                {
                    $result = mysqli_query($database, "SELECT * FROM `ideas` WHERE `stageid` = $search");
                }

                if (mysqli_num_rows($result) > 0)
                {
                    while ($row = mysqli_fetch_row($result))
                    {
                        echo '<div class="row bg-light align-items-center border-bottom">';
                        echo '<div class="col-3 fs-5 ms-3 pt-3">';
                        echo '<p><b>'.$row[1];
                        $stage = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `stages` WHERE `stageid` = $row[7]"));
                        echo '</b><br><i><a href="/ideas.php?search='.$stage[0].'" class="link-primary">'.$stage[1].'</a></i></p>';
                        $author = mysqli_fetch_row(mysqli_query($database, "SELECT `login` FROM `users` WHERE `userid` = $row[4]"));
                        echo '<p><b>Автор:</b><br>'.$author[0].'</p>';
                        echo '</div>';
                        echo '<div class="col-5 fs-6">';
                        echo '<p><b>Описание:</b><br>'.$row[3].'</p>';
                        $fee = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `fees` WHERE `feeid` = $row[5]"));
                        if ($fee[0] == 1) { $fee_type = ' руб.'; }
                        else { $fee_type = '%'; }
                        echo '<p><b>Вид отчислений: </b>'.$fee[1].' в размере '.$row[6], $fee_type.'</p>';
                        echo '</div>';
                        echo '<div class="col-3 ms-auto">';
                        echo '<a class="btn btn-outline-dark" href="/idea.php?id='.$row[0].'" role="button">Подробнее...</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                else
                {
                    echo '<div class="row bg-light">';
                    echo '<p class=" fs-5 ms-3 pt-3">нет идей ;(</p>';
                    echo '</div>';
                }

                
                mysqli_close($database);
            ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>