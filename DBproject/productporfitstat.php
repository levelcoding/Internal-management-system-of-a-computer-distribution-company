<?php
    $pid=$_GET["pid"];

    include 'conn.php';

    $sql="SELECT productID,customerID,name,sum(number) AS num FROM orderdetial NATURAL JOIN orders NATURAL JOIN customer WHERE productID='". $pid."' GROUP BY customerID DESC LIMIT 0,4;";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td class='col-md-5'>".$row['customerID']."</td>";
        echo "<td class='col-md-5'>".$row['name']."</td>";
        echo "</tr>";
    }
    mysqli_close($conn);