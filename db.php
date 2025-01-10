<?php
$serverName = "DESKTOP-4ICK6BG\MSSQLSERVER01"; 

$username = "st968";       
$password = "abc123";     
$database = "skleprtv"; 


$connectionOptions = array(
    "Database" => $database,
    "UID" => $username,
    "PWD" => $password,
    "Encrypt" => true,  // Włączenie szyfrowania SSL
    "TrustServerCertificate" => true,  // Ufaj certyfikatowi serwera, nawet jeśli jest samopodpisany
);

// Próba połączenia
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Sprawdzanie połączenia
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true)); // Wyświetlenie błędów
} else {
    //echo "Połączono z bazą danych MSSQL!";
}
?>