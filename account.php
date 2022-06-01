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

    if($_POST && isset($_POST['titleCategory']))
    {
        $title = $_POST['titleCategory'];
        $database = mysqli_connect('localhost', 'root', '', 'creator');

        if (mysqli_connect_errno())
        {
            echo '<div class="alert alert-danger" role="alert">
            <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
            </div>';
            exit();
        }
        
        $result = mysqli_query($database, "INSERT INTO `categories` VALUES (NULL, '$title')");
        if ($result)
        {
            echo '<div class="alert alert-success" role="alert">
            <strong>Категория была добавлена в базу данных.</strong>
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

        <title>Личный кабинет</title>
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
                                <a class="nav-link active" aria-current="page" href="/account.php">'.$_SESSION['login'].'</a>
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
            <div class="row text-light fs-3 ms-3">Личный кабинет</div>
            <div class="row bg-light">
                <?php
                    if (!isset($_SESSION['userid']))
                    {
                        echo '<div class="alert alert-info mt-3" role="alert">
                        <strong>Чтобы воспользоваться личным кабинетом, пройдите авторизацию!</strong>
                        </div></div></div>';
                        exit();
                    }
                ?>
                <div class="col-3 fs-4 text-center">
                    <?php
                        $userid = $_SESSION['userid'];
                        $database = mysqli_connect('localhost', 'root', '', 'creator');

                        if (mysqli_connect_errno())
                        {
                            echo '<div class="alert alert-danger" role="alert">
                            <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
                            </div>';
                            exit();
                        }

                        $user_info = mysqli_query($database, "SELECT * FROM `users` WHERE `UserID` = $userid");
                        $info = mysqli_fetch_row($user_info);
                        if ($info[6] == 0) { echo '<p>Пользователь</p>'; }
                        else { echo '<p>Администратор</p>'; }
                    ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                    </svg>
                    <?php
                        echo '<p><strong>'.$info[2].'</strong></p>';
                        echo '<p>'.$info[1].'</p>';
                    ?>    
                </div>
                <div class="col bg-white fs-4">
                    <p class="mt-3 fw-bold">Ваши идеи:</p>
                    <?php
                        $user_ideas = mysqli_query($database, "SELECT * FROM `ideas` WHERE `UserID` = $userid");

                        if (mysqli_num_rows($user_ideas) > 0)
                        {
                            echo '<div class="list-group fs-5">';
                            while ($idea = mysqli_fetch_row($user_ideas))
                            {
                                echo '<a href="/idea.php?id='.$idea[0].'" class="list-group-item list-group-item-action">'.$idea[1].'</a>';
                            }
                            echo '</div>';
                        }
                        else
                        {
                            echo '<p><i>идеи не найдены ;(</i></p>';
                        }
                    ?>
                    <?php
                        if (isset($_SESSION['status']) && $_SESSION['status'] == 1)
                        {
                            echo '<p class="mt-3 fw-bold">Панель администратора:</p>';
                            echo '<div class="card card-body w-75 mb-3">';
                            echo '<form method="POST" action="/account.php">';
                            echo '<label for="idIdea" class="form-label fs-5">Управление идеями</label>
                            <select class="form-select mb-3" aria-label="Выберите идею" name="idIdea">';
                            $ideas_list = mysqli_query($database, "SELECT * FROM `ideas`");
                            while ($idea = mysqli_fetch_row($ideas_list))
                            {
                                echo '<option value="'.$idea[0].'">'.$idea[1].' [ID:'.$idea[0].']</option>';
                            }
                            echo '</select>';
                            echo '<button type="submit" class="btn btn-outline-danger">Удалить</button>';
                            echo '</form>';
                            echo '<form method="POST" action="/account.php">';
                            echo '<label for="titleCategory" class="form-label fs-5">Управление категориями</label>
                            <input type="text" class="form-control mb-3" id="titleCategory" name="titleCategory" maxlength="16" required>';
                            echo '<button type="submit" class="btn btn-outline-success">Добавить</button>';
                            echo '</form>';
                            // круговая диаграмма
                            echo '<a class="btn btn-outline-dark mb-3" data-bs-toggle="collapse" href="#statistics1" role="button" aria-expanded="false" aria-controls="statistics1">Статистика по степеням проработанности</a>';
                            echo '<div class="collapse mb-3" id="statistics1">';
                            echo '<div class="card card-body">';
                            // google-charts begin
                            echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
                            echo '<script type="text/javascript">';
                            echo "google.charts.load('current', {'packages':['corechart']});";
                            echo "google.charts.setOnLoadCallback(drawChart);";
                            echo "function drawChart() {
                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Степень проработанности');
                                data.addColumn('number', 'Количество идей');";
                            
                            $ideas_by_stage = mysqli_query($database, "SELECT `StageID`, COUNT(*) FROM `ideas` GROUP BY `StageID`");
                            while($stage_stats = mysqli_fetch_row($ideas_by_stage))
                            {
                                $stage_title = mysqli_fetch_row(mysqli_query($database, "SELECT `Title` FROM `stages` WHERE `StageID` = ".$stage_stats[0]));
                                echo "data.addRow(['".$stage_title[0]."', ".$stage_stats[1]."]);";
                            }

                            echo "var options = {'title':'Круговая диаграмма по степеням проработанности идей, размещенных на сайте',
                                'width':550,
                                'height':200};
         
                                var chart = new google.visualization.PieChart(document.getElementById('chart1'));
                                chart.draw(data, options);
                            }
                            </script>";
                            echo '<div id="chart1"></div>';
                            // google-charts end
                            echo '<p><a href="/pdf.php?type=1" class="link-primary fs-5">Таблица (pdf)</a></p>';
                            echo '</div>';
                            echo '</div>';

                            echo '<a class="btn btn-outline-dark mb-3" data-bs-toggle="collapse" href="#statistics2" role="button" aria-expanded="false" aria-controls="statistics2">Статистика по категориям</a>';
                            echo '<div class="collapse mb-3" id="statistics2">';
                            echo '<div class="card card-body">';
                            // google-charts begin
                            echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
                            echo '<script type="text/javascript">';
                            echo "google.charts.load('current', {'packages':['corechart']});";
                            echo "google.charts.setOnLoadCallback(drawChart);";
                            echo "function drawChart() {
                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Категория');
                                data.addColumn('number', 'Количество идей');";
                            
                            $ideas_by_category = mysqli_query($database, "SELECT `CategoryID`, COUNT(*) FROM `ideas` GROUP BY `CategoryID`");
                            while($category_stats = mysqli_fetch_row($ideas_by_category))
                            {
                                $category_title = mysqli_fetch_row(mysqli_query($database, "SELECT `Title` FROM `categories` WHERE `CategoryID` = ".$category_stats[0]));
                                echo "data.addRow(['".$category_title[0]."', ".$category_stats[1]."]);";
                            }

                            echo "var options = {'title':'Круговая диаграмма по категориям идей, размещенных на сайте',
                                'width':550,
                                'height':200,
                                'pieHole':0.4};
         
                                var chart = new google.visualization.PieChart(document.getElementById('chart2'));
                                chart.draw(data, options);
                            }
                            </script>";
                            echo '<div id="chart2"></div>';
                            // google-charts end
                            echo '<p><a href="/pdf.php?type=2" class="link-primary fs-5">Таблица (pdf)</a></p>';
                            echo '</div>';
                            echo '</div>';

                            echo '<a class="btn btn-outline-dark mb-3" data-bs-toggle="collapse" href="#statistics3" role="button" aria-expanded="false" aria-controls="statistics3">Статистика активности авторов</a>';
                            echo '<div class="collapse mb-3" id="statistics3">';
                            echo '<div class="card card-body">';
                            // google-charts begin
                            echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
                            echo '<script type="text/javascript">';
                            echo "google.charts.load('current', {'packages':['corechart']});";
                            echo "google.charts.setOnLoadCallback(drawChart);";
                            echo "function drawChart() {
                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Пользователь');
                                data.addColumn('number', 'Количество идей');";
                            
                            $ideas_by_users = mysqli_query($database, "SELECT `UserID`, COUNT(*) FROM `ideas` GROUP BY `UserID`");
                            while($user_stats = mysqli_fetch_row($ideas_by_users))
                            {
                                $user_login = mysqli_fetch_row(mysqli_query($database, "SELECT `Login` FROM `users` WHERE `UserID` = ".$user_stats[0]));
                                echo "data.addRow(['".$user_login[0]."', ".$user_stats[1]."]);";
                            }

                            echo "var options = {'title':'Диаграмма активности авторов',
                                'width':550,
                                'height':200,
                                'legend': { position: 'none' }
                            };
         
                                var chart = new google.visualization.BarChart(document.getElementById('chart3'));
                                chart.draw(data, options);
                            }
                            </script>";
                            echo '<div id="chart3"></div>';
                            // google-charts end
                            echo '<p><a href="/pdf.php?type=3" class="link-primary fs-5">Таблица (pdf)</a></p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        mysqli_close($database);
                    ?>
                </div>       
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>