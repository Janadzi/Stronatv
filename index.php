<?php
session_start();
include 'db.php'; // Plik z połączeniem do bazy danych

// Sprawdzenie, czy użytkownik jest zalogowany
$is_logged_in = isset($_SESSION['ID_Uzytkownika']);
$user_name = $is_logged_in ? $_SESSION['Imie'] : '';

// Pobieranie produktów z bazy danych
$query = "SELECT TOP 3 * FROM Produkt ORDER BY ID_Produktu"; // Pobierz tylko 3 produkty
$result = sqlsrv_query($conn, $query);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechHouse</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="header">
    <div class="logo">TECHHOUSE</div>
    <div class="search-bar">
      <input type="text" placeholder="Wyszukaj w sklepie">
      <button>Szukaj</button>
    </div>
    <div class="user-menu">
      <?php if ($is_logged_in): ?>
        <p>Witaj, <?= htmlspecialchars($user_name) ?>!</p>
        <a href="logout.php">Wyloguj</a>
        <a href="cart.php">Koszyk</a>
        <a href="admin.php">Panel Admina</a>
        <a href="zamowienia.php">Zamówienia</a>
        <a href="profil.php">Profil</a>
      <?php else: ?>
        <a href="register.php">Rejestracja</a>
        <a href="login.php">Logowanie</a>
      <?php endif; ?>
    </div>
  </header>

  <main>
    <section class="banner">
      <h1>TU IDZIE BANNER</h1>
    </section>

    <section class="content">
      <aside class="sidebar">
        <h2>Produkty</h2>
        <ul>
          <li><input type="checkbox"> TV, audio i RTV</li>
          <li><input type="checkbox"> AGD</li>
          <li><input type="checkbox"> AGD do zabudowy</li>
          <li><input type="checkbox"> AGD małe</li>
        </ul>
      </aside>

      <div class="products">
        <h2>Polecane Produkty</h2>
        <div class="product-list">
          <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
            <div class="product">
              <img src="https://via.placeholder.com/150" alt="Produkt">
              <h3><?= htmlspecialchars($row['Nazwa_Produktu']) ?></h3>
              <p>⭐⭐⭐⭐⭐</p>
              <p><?= number_format($row['Cena'], 2) ?> zł</p>
              <a href="product.php?id=<?= htmlspecialchars($row['ID_Produktu']) ?>">Zobacz produkt</a>
            </div>
          <?php endwhile; ?>

          <!-- Sztywne produkty (dla testów) -->
          <div class="product">
            <img src="https://via.placeholder.com/150" alt="JBL Speaker">
            <h3>Głośnik mobilny JBL Go4 Czarny</h3>
            <p>⭐⭐⭐⭐⭐</p>
            <p>199.99 zł</p>
            <a href="product.php?id=1">Zobacz produkt</a>
          </div>
          <div class="product">
            <img src="https://via.placeholder.com/150" alt="JBL Speaker">
            <h3>Głośnik mobilny JBL Go4 Czarny</h3>
            <p>⭐⭐⭐⭐⭐</p>
            <p>199.99 zł</p>
            <a href="product.php?id=2">Zobacz produkt</a>
          </div>
          <div class="product">
            <img src="https://via.placeholder.com/150" alt="JBL Speaker">
            <h3>Głośnik mobilny JBL Go4 Czarny</h3>
            <p>⭐⭐⭐⭐⭐</p>
            <p>199.99 zł</p>
            <a href="product.php?id=3">Zobacz produkt</a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php 
  // Zwolnienie zasobów
  sqlsrv_free_stmt($result);
  sqlsrv_close($conn);
  ?>
</body>
</html>
