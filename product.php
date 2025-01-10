<?php
include 'db.php'; // Połączenie z bazą danych

// Pobieranie ID produktu z parametru URL
$product_id = $_GET['id'] ?? 0;

// Jeśli ID jest nieprawidłowe, wyświetlamy błąd
if ($product_id <= 0) {
    die("Nieprawidłowy ID produktu.");
}

// Pobieranie szczegółów produktu z bazy danych
$query = "SELECT * FROM Produkt WHERE ID_Produktu = ?";
$params = [$product_id];
$stmt = sqlsrv_prepare($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

sqlsrv_execute($stmt);
$product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Jeśli produkt nie istnieje, wyświetlamy błąd
if (!$product) {
    die("Produkt nie znaleziony.");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['Nazwa_Produktu']) ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="header">
    <div class="logo">TECHHOUSE</div>
    <div class="user-menu">
      <a href="#">Moje Konto</a>
      <a href="#">Koszyk</a>
    </div>
  </header>

  <main>
    <section class="product-details">
      <h1><?= htmlspecialchars($product['Nazwa_Produktu']) ?></h1>
      <img src="https://via.placeholder.com/300" alt="<?= htmlspecialchars($product['Nazwa_Produktu']) ?>">
      <p><?= number_format($product['Cena'], 2) ?> zł</p>
      <p>⭐⭐⭐⭐⭐</p>
      <p><?= htmlspecialchars($product['Opis']) ?></p>
      <button>Dodaj do koszyka</button>
    </section>
  </main>

  <?php 
  // Zwolnienie zasobów
  sqlsrv_free_stmt($stmt);
  sqlsrv_close($conn);
  ?>
</body>
</html>
