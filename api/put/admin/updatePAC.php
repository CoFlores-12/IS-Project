<?php

include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "SELECT * FROM Periods WHERE active = 1";

$result = $conn->execute_query($sql);
$row = $result->fetch_assoc();
$ind = intval($row['indicator'])+1;
$year = intval($row['year']);
if ($ind == 4) {
    $ind = 1;
    $year++;
}

$sql = "UPDATE Periods SET active = 0 WHERE active = 1";
$result = $conn->execute_query($sql);
$sql = "INSERT INTO Periods (indicator, year, active) VALUES (?, ?, 1)";
$result = $conn->execute_query($sql, [$ind, $year]);

echo json_encode(["message"=>$ind.' PAC '.$year]);