<?php
session_start();
include 'db.php'; // Połączenie z bazą danych

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['ID_Uzytkownika'])) {
    die("Musisz być zalogowany, aby wyświetlić koszyk.");
}

// Pobranie ID użytkownika
$id_uzytkownika = $_SESSION['ID_Uzytkownika']; 

// Znalezienie najnowszego koszyka dla użytkownika
$query = "SELECT TOP 1 * FROM Koszyk WHERE ID_Uzytkownika = ? ORDER BY Data_Utworzenia DESC";
$params = [$id_uzytkownika];
$stmt = sqlsrv_prepare($conn, $query, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

sqlsrv_execute($stmt);
$koszyk = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Jeśli nie znaleziono koszyka
if (!$koszyk) {
    die("Nie masz aktywnego koszyka.");
}

// Pobranie produktów w tym koszyku
$koszyk_id = $koszyk['ID_Koszyka'];
$query_products = "SELECT p.ID_Produktu, p.Nazwa_Produktu, p.Cena, kp.Ilosc
                   FROM Koszyk_Produkt kp
                   JOIN Produkt p ON p.ID_Produktu = kp.ID_Produktu
                   WHERE kp.ID_Koszyka = ?";
$stmt_products = sqlsrv_prepare($conn, $query_products, [$koszyk_id]);
if ($stmt_products === false) {
    die(print_r(sqlsrv_errors(), true));
}

sqlsrv_execute($stmt_products);

// Sprawdzenie, czy w zapytaniu są produkty
$products = [];
while ($row = sqlsrv_fetch_array($stmt_products, SQLSRV_FETCH_ASSOC)) {
    $products[] = $row; // Dodajemy każdy produkt do tablicy
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twój Koszyk</title>
</head>
<body>
    <h1>Twój Koszyk</h1>
    <p>Status koszyka: <?= htmlspecialchars($koszyk['Status']) ?></p>
    <p>Data utworzenia: <?= $koszyk['Data_Utworzenia']->format('Y-m-d H:i:s') ?></p>

    <h2>Produkty w koszyku</h2>
    <?php
    // Jeśli są produkty, wyświetl je
    if (count($products) > 0) {
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Nazwa Produktu</th>
                        <th>Cena</th>
                        <th>Ilosc</th>
                        <th>Łączna cena</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($products as $row) {
            $total_price = $row['Cena'] * $row['Ilosc'];
            echo "<tr>
                    <td>" . htmlspecialchars($row['Nazwa_Produktu']) . "</td>
                    <td>" . number_format($row['Cena'], 2) . " zł</td>
                    <td>" . $row['Ilosc'] . "</td>
                    <td>" . number_format($total_price, 2) . " zł</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>Twój koszyk jest pusty.</p>";
    }

    // Zwolnienie zasobów
    sqlsrv_free_stmt($stmt);
    sqlsrv_free_stmt($stmt_products);
    sqlsrv_close($conn);
    ?>
</body>
</html>
