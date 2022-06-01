<?php
session_start();
$log=$_SESSION["newsession"];

        mysql_connect('localhost','root','');
        mysql_select_db('med');
       mysql_query("set character set cp1251");
		
		

		
		//define('FPDF_FONTPATH',"fpdf/font/");
    require_once("fpdf/fpdf.php");
	
       $pdf = new FPDF('L', 'pt', 'A4');
    #$pdf->SetAuthor("$id_user");
    $pdf->AddFont('Arial','','courier.php');
    $pdf->SetFont('Arial');
    $pdf->SetFontSize(20);
    $pdf->AddPage();
    $pdf->SetDisplayMode('real', 'default');
	


    $pdf->Cell(0, 30, "Сайт клиники Меговит", 0, 2, 'C');


		    $pdf->Ln(10);
    $pdf->Cell(0, 30,  "Результаты анализа", 0, 1, 'C');
    $pdf->Ln(32);
////////////////////
////////////////
        $result = mysql_query("select * from customers where login = $log");
        $row = mysql_fetch_row($result);
	
		        $pdf->Cell(0, 30, "ФИО пациента: $row[2]", 0, 2, 'L');
        ////////////
    $pdf->SetFontSize(16);
    $pdf->Ln(32);
    $pdf->SetTextColor(0, 100, 0);
    $pdf->SetFillColor(152, 251, 152);
    $pdf->SetLineWidth(1);
    $pdf->Cell(130, 25,  "Название", 1, 0, 'C', 1);
    $pdf->Cell(130, 25, "Значение", 'LTR', 0, 'C', true);
    $pdf->Cell(200, 25, "Ед. измерения", 'LTR', 0, 'C', true);
    $pdf->Cell(160, 25, "Нормальное значение", 'LTR', 0, 'C', true);
    $pdf->Cell(160, 25,  "Статус", 'LTR', 1, 'C', true);
	 
    $k = 1;

  
		$result1=mysql_query("select (select title from services where services.codeserv=results.codeserv),meaning,unit,norm,status from results where orderid in (select orderid from orders where cuserid in(SELECT cuserid FROM customers where login=$log))");

while ($row = mysql_fetch_row($result1))
{
    // $result2=mysql_query("select flight.number,city_fromwhere,city_where,price,quantity,category,date1,flight.date,time1,time2,plane,amount,name from flight,order_items,orders,login_password,customers where order_items.orderid='$row[3]' and order_items.number=flight.number and login_password.login='$login' and login_password.password='$password' and login_password.customerid=orders.customerid and customers.customerid=orders.customerid and orders.date='$row[1]' and amount='$row[2]' and name='$row[0]'");
   
  // while ($row2=mysql_fetch_row($result2))
//{
            $pdf->SetFontSize(14);
            $pdf->SetTextColor(25, 25, 112);
            $pdf->Cell(130, 20, "$row[0]", 1, 0, 'C');
            $pdf->Cell(130, 20,  "$row2[1] ", 1, 0, 'C');
            $pdf->Cell(200, 20,  "$row2[2] ", 1, 0, 'C');
            $pdf->Cell(160, 20,  "$row2[3] ", 1, 0, 'C');
            $pdf->Cell(160, 20,  "$row[4] ", 1, 1, 'C');		
			 
//}
		
}
			
	    $pdf->Ln(32);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 30,  "Информация предоставлена администратором сайта", 0, 0, 'L');

    $pdf->AddFont('Arial','','courier.php');
    $pdf->SetFont('Arial');
    $pdf->SetFontSize(20);
    $pdf->Cell(-150, 30, iconv('utf-8', 'windows-1251', "                   "), 0, 0, 'R');

    $pdf->AddFont('Arial','','courier.php');
    $pdf->SetFont('Arial');
    $pdf->SetFontSize(16);
    $pdf->Cell(0, 30, "Орешкин И.В.", 0, 1, 'R');
      $file_name = $row[1].".pdf";
    $pdf->Output("result.pdf", "I");
    $pdf->Output("$file_name", "F");







       /* $nfinder = mysql_query("select * from customers where login = $log");
        $nrow = mysql_fetch_row($nfinder);
        echo '<div class="park__text">Просмотр результатов заказа №'.$nrow[1].'</div>';
		echo '<div >Ф.И.О. ПАЦИЕНТА:  '.$nrow[2].'</div>';
		echo '<div >ДАТА РОЖДЕНИЯ ПАЦИЕНТА:  '.$nrow[3].'</div>';
		echo '<div >ПОЛ ПАЦИЕНТА:  '.$nrow[4].'</div>';
		
		$dfinder = mysql_query("select * from orders, customers where orders.cuserid = customers.cuserid and customers.login=$log");
		$drow = mysql_fetch_row($dfinder);
		echo '<div >ДАТА ЗАКАЗА:  '.$drow[2].'</div></br>';

		
		/*$i = 0;
		while ($drow = mysql_fetch_row($dfinder))
		{
			echo '<p> ДАТА ЗАКАЗА: ';
			echo stripslashes($drow[2]);


		}*/
		
		//$resfinder = mysql_query("select * from results, orders, customers where results.orderid = orders.orderid and orders.cuserid = customers.cuserid and customers.login=$log");
       // $resrow = mysql_fetch_row($resfinder);
		
		//$tfinder = mysql_query("select * from services, results, orders, customers where services.codeserv = results.codeserv and results.orderid = orders.orderid and orders.cuserid = customers.cuserid and customers.login=$log");
        //$trow = mysql_fetch_row($tfinder);
		
		// echo '<div class="park__text">Результаты заказа</div>';


		/*$i = 0;
		while ($resrow = mysql_fetch_row($resfinder))
		{
			echo '<p> Код: ';
			echo stripslashes($resrow[1]);
			
		$tfinder = mysql_query("select * from services, results, orders, customers where services.codeserv = results.codeserv and results.orderid = orders.orderid and orders.cuserid = customers.cuserid and customers.login=$log");
			
			while ($trow = mysql_fetch_row($tfinder))
			{
				echo '<br />Название исследования: ';
				echo stripslashes($trow[1]);
			}
			
				/*$trow = mysql_fetch_row($tfinder)
				echo '<br />Название исследования: ';
				echo stripslashes($trow[1]);
			while ($resrow = mysql_fetch_row($resfinder))
		{
			echo '<br />Значение: ';
			echo stripslashes($resrow[2]);
			
			echo '<br />Ед. измерения: ';
			echo stripslashes($resrow[3]);
			
			echo '<br />Нормальные значения: ';
			echo stripslashes($resrow[4]);
			
			echo '<br />Статус: ';
			echo stripslashes($resrow[5]);
			
			echo '</p>';
			$i=$i+1;
		}*/


		
		
        /*echo '<div class="park__text">Результаты заказа</div>';
		echo '<div >Код:  '.$resrow[1].'</div>';
		echo '<div >Название исследования:  '.$trow[1].'</div>';
		echo '<div >Значение:  '.$resrow[2].'</div>';
		echo '<div >Ед. измерения:  '.$resrow[3].'</div>';
		echo '<div >Нормальные значения:</br>  '.$resrow[4].'</div>';
		echo '<div >Статус:  '.$resrow[5].'</div>';
        echo '</div></br>';
		
		        $result=mysql_query("select codeserv, (select title from services where services.codeserv=results.codeserv),meaning,unit,norm,status from results where orderid in (select orderid from orders where cuserid in(SELECT cuserid FROM customers where login=$log))");
                while ($row = mysql_fetch_row($result))
                    {   
						echo '</div></br>';
						echo '<div>Код исследования: '.$row[0].'</div>';
                        echo '<div>Название исследования: '.$row[1].'</div>';
                        echo '<div>Значение: '.$row[2].'</div>';
                        echo '<div>Ед. измерения: '.$row[3].'</div>';
                        echo '<div>Нормальное значение: </br> '.$row[4].'</div>';
                        echo '<div>Статус: '.$row[5].'</div>';
                    }
				echo '</div></br>';
		
		
        mysql_close();*/

    ?>
