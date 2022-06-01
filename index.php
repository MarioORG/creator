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
        
        <title>Добро пожаловать!</title>
    </head>
    <body class="bg-primary font-monospace">
        <div class="container-fluid bg-dark position-absolute top-50 start-0 translate-middle-y">
            <div class="row bg-light mt-5">
                <div class="col-4">
                    <p class="fs-1 fw-bold mt-3">Добро пожаловать!</p>
                    <p class="fs-2">Выберите раздел для начала работы с сервисом</p>
                </div>
                <div class="col">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action fs-1 bg-light">Найти работу</a>
                        <a href="#" class="list-group-item list-group-item-action fs-1 bg-light">Найти работников</a>
                        <a href="/ideas.php" class="list-group-item list-group-item-action fs-1 bg-light">Найти идею</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>