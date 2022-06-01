<?php
    session_start();

    define('FPDF_FONTPATH',"fpdf/font/");
    require_once("fpdf/fpdf.php");

    $database = mysqli_connect('localhost', 'root', '', 'creator');

    if (mysqli_connect_errno())
    {
        echo '<div class="alert alert-danger" role="alert">
        <strong>Ошибка: не удалось установить соединение с базой данных.</strong>
        </div>';
        exit();
    }

    if($_GET['idea'])
    {
        $id = $_GET['idea'];

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetTitle("CREATOR | Idea");
        $pdf->AddPage();
        $pdf->AddFont('Arial', '', 'arial.php'); 

        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->SetFont( 'Arial', '', 12 );
        $pdf->Cell( 0, 10, iconv('utf-8', 'windows-1251', 'КРЕАТОР'), 0, 0, 'C' );
        $pdf->Ln(10);

        $idea_info = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `ideas` WHERE `IdeaID` = $id"));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 24);
        $pdf->Write(19, iconv('utf-8', 'windows-1251', $idea_info[1]));
        $pdf->Ln(10);
        $pdf->SetFont( 'Arial', '', 18 );
        $author = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `users` WHERE `userid` = $idea_info[4]"));
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Автор: '.$author[2]));
        $pdf->Ln(10);
        $category = mysqli_fetch_row(mysqli_query($database, "SELECT `title` FROM `categories` WHERE `categoryid` = $idea_info[2]"));
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Категория: '.$category[0]));
        $pdf->Ln(10);
        $stage = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `stages` WHERE `stageid` = $idea_info[7]"));
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Степень проработанности: '.$stage[1]));
        $pdf->Ln(10);
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Описание:'));
        $pdf->Ln(14);
        $pdf->SetFont('Arial', '', 16);
        $pdf->MultiCell(0, 10, iconv('utf-8', 'windows-1251', $idea_info[3]));
        $pdf->Ln(10);
        $fee = mysqli_fetch_row(mysqli_query($database, "SELECT * FROM `fees` WHERE `feeid` = $idea_info[5]"));
        if ($fee[0] == 1) { $fee_type = ' руб.'; }
        else { $fee_type = '%'; }
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Вид отчислений: '.$fee[1].' в размере '.$idea_info[6].$fee_type));
        $pdf->Ln(10);
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Связь с автором (e-mail): '.$author[4]));

        $pdf->Output("idea.pdf", "I");
        exit();
    }

    if (!isset($_SESSION['status']) || $_SESSION['status'] != 1)
    {
        echo 'Ошибка доступа!';
        exit();
    }

    if($_GET['type'] == 1)
    {
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->SetTitle("CREATOR | Statistics");
        $pdf->AddPage();
        $pdf->AddFont('Arial', '', 'arial.php'); 

        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->SetFont( 'Arial', '', 12 );
        $pdf->Cell( 0, 10, iconv('utf-8', 'windows-1251', 'КРЕАТОР'), 0, 0, 'C' );
        $pdf->Ln(10);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 20);
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Статистика по степеням проработанности идей, размещенных на сайте'));
        $pdf->Ln(16);
        $pdf->SetFont( 'Arial', '', 14 );
        $pdf->SetFillColor(111, 168, 220);
        $pdf->Cell(8, 10, iconv('utf-8', 'windows-1251', '№'), 1, 0, 'C', true);
        $pdf->Cell(125, 10, iconv('utf-8', 'windows-1251', 'Степень проработанности'), 1, 0, 'L', true);
        $pdf->Cell(75, 10, iconv('utf-8', 'windows-1251', 'Количество идей'), 1, 0, 'C', true);
        $pdf->Cell(60, 10, iconv('utf-8', 'windows-1251', 'Проценты'), 1, 0, 'C', true);
        $pdf->Ln(10);
        $pdf->SetFillColor(255, 255, 255);

        $row = 1;
        $sum = mysqli_fetch_row(mysqli_query($database, "SELECT COUNT(*) FROM `ideas`"));
        $ideas_by_stage = mysqli_query($database, "SELECT `StageID`, COUNT(*) FROM `ideas` GROUP BY `StageID`");
        while($stage_stats = mysqli_fetch_row($ideas_by_stage))
        {
            $stage_title = mysqli_fetch_row(mysqli_query($database, "SELECT `Title` FROM `stages` WHERE `StageID` = ".$stage_stats[0]));
            $pdf->Cell(8, 10, $row, 1, 0, 'C', true);
            $pdf->Cell(125, 10, iconv('utf-8', 'windows-1251', $stage_title[0]), 1, 0, 'L', true);
            $pdf->Cell(75, 10, iconv('utf-8', 'windows-1251', $stage_stats[1]), 1, 0, 'C', true);
            $pdf->Cell(60, 10, round($stage_stats[1] / $sum[0] * 100, 2).'%', 1, 0, 'C', true);
            $pdf->Ln();
            $row++;
        }

        $pdf->Cell(133, 10, iconv('utf-8', 'windows-1251', 'Итого:'), 1, 0, 'L', true);
        $pdf->Cell(75, 10, $sum[0], 1, 0, 'C', true);
        $pdf->Cell(60, 10, '100%', 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->Output("idea.pdf", "I");
        exit();
    }

    if($_GET['type'] == 2)
    {
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->SetTitle("CREATOR | Statistics");
        $pdf->AddPage();
        $pdf->AddFont('Arial', '', 'arial.php'); 

        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->SetFont( 'Arial', '', 12 );
        $pdf->Cell( 0, 10, iconv('utf-8', 'windows-1251', 'КРЕАТОР'), 0, 0, 'C' );
        $pdf->Ln(10);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 20);
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Статистика по категориям идей, размещенных на сайте'));
        $pdf->Ln(16);
        $pdf->SetFont( 'Arial', '', 14 );
        $pdf->SetFillColor(111, 168, 220);
        $pdf->Cell(8, 10, iconv('utf-8', 'windows-1251', '№'), 1, 0, 'C', true);
        $pdf->Cell(125, 10, iconv('utf-8', 'windows-1251', 'Категория'), 1, 0, 'L', true);
        $pdf->Cell(75, 10, iconv('utf-8', 'windows-1251', 'Количество идей'), 1, 0, 'C', true);
        $pdf->Cell(60, 10, iconv('utf-8', 'windows-1251', 'Проценты'), 1, 0, 'C', true);
        $pdf->Ln(10);
        $pdf->SetFillColor(255, 255, 255);

        $row = 1;
        $sum = mysqli_fetch_row(mysqli_query($database, "SELECT COUNT(*) FROM `ideas`"));
        $ideas_by_category = mysqli_query($database, "SELECT `CategoryID`, COUNT(*) FROM `ideas` GROUP BY `CategoryID`");
        while($category_stats = mysqli_fetch_row($ideas_by_category))
        {
            $category_title = mysqli_fetch_row(mysqli_query($database, "SELECT `Title` FROM `categories` WHERE `CategoryID` = ".$category_stats[0]));
            $pdf->Cell(8, 10, $row, 1, 0, 'C', true);
            $pdf->Cell(125, 10, iconv('utf-8', 'windows-1251', $category_title[0]), 1, 0, 'L', true);
            $pdf->Cell(75, 10, iconv('utf-8', 'windows-1251', $category_stats[1]), 1, 0, 'C', true);
            $pdf->Cell(60, 10, round($category_stats[1] / $sum[0] * 100, 2).'%', 1, 0, 'C', true);
            $pdf->Ln();
            $row++;
        }

        $pdf->Cell(133, 10, iconv('utf-8', 'windows-1251', 'Итого:'), 1, 0, 'L', true);
        $pdf->Cell(75, 10, $sum[0], 1, 0, 'C', true);
        $pdf->Cell(60, 10, '100%', 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->Output("idea.pdf", "I");
        exit();
    }

    if($_GET['type'] == 3)
    {
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->SetTitle("CREATOR | Statistics");
        $pdf->AddPage();
        $pdf->AddFont('Arial', '', 'arial.php'); 

        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->SetFont( 'Arial', '', 12 );
        $pdf->Cell( 0, 10, iconv('utf-8', 'windows-1251', 'КРЕАТОР'), 0, 0, 'C' );
        $pdf->Ln(10);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 20);
        $pdf->Write(19, iconv('utf-8', 'windows-1251', 'Статистика активности авторов'));
        $pdf->Ln(16);
        $pdf->SetFont( 'Arial', '', 14 );
        $pdf->SetFillColor(111, 168, 220);
        $pdf->Cell(8, 10, iconv('utf-8', 'windows-1251', '№'), 1, 0, 'C', true);
        $pdf->Cell(125, 10, iconv('utf-8', 'windows-1251', 'Логин'), 1, 0, 'L', true);
        $pdf->Cell(75, 10, iconv('utf-8', 'windows-1251', 'Количество идей'), 1, 0, 'C', true);
        $pdf->Cell(60, 10, iconv('utf-8', 'windows-1251', 'Проценты'), 1, 0, 'C', true);
        $pdf->Ln(10);
        $pdf->SetFillColor(255, 255, 255);

        $row = 1;
        $sum = mysqli_fetch_row(mysqli_query($database, "SELECT COUNT(*) FROM `ideas`"));
        $ideas_by_users = mysqli_query($database, "SELECT `UserID`, COUNT(*) FROM `ideas` GROUP BY `UserID`");
        while($user_stats = mysqli_fetch_row($ideas_by_users))
        {
            $user_login = mysqli_fetch_row(mysqli_query($database, "SELECT `Login` FROM `users` WHERE `UserID` = ".$user_stats[0]));
            $pdf->Cell(8, 10, $row, 1, 0, 'C', true);
            $pdf->Cell(125, 10, iconv('utf-8', 'windows-1251', $user_login[0]), 1, 0, 'L', true);
            $pdf->Cell(75, 10, iconv('utf-8', 'windows-1251', $user_stats[1]), 1, 0, 'C', true);
            $pdf->Cell(60, 10, round($user_stats[1] / $sum[0] * 100, 2).'%', 1, 0, 'C', true);
            $pdf->Ln();
            $row++;
        }

        $pdf->Cell(133, 10, iconv('utf-8', 'windows-1251', 'Итого:'), 1, 0, 'L', true);
        $pdf->Cell(75, 10, $sum[0], 1, 0, 'C', true);
        $pdf->Cell(60, 10, '100%', 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->Output("idea.pdf", "I");
        exit();
    }

    mysqli_close($database);
?>