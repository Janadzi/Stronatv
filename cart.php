<?php
session_start();
include 'db.php'; // Połączenie z bazą danych, np. SQL Server lub MySQL

// Funkcja do obliczenia łącznej ceny
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Dodawanie produktu do koszyka
if (isset($_GET['add'])) {
    $productId = (int)$_GET['add'];
    $quantity = (int)$_GET['quantity'] ?? 1;

    // Sprawdzenie, czy produkt już jest w koszyku
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Pobranie danych o produkcie z bazy
        $query = "SELECT ID_Produktu, Nazwa_Produktu, Cena FROM Produkt WHERE ID_Produktu = ?";
        $params = [$productId];
        $stmt = sqlsrv_prepare($conn, $query, $params);
        if (sqlsrv_execute($stmt)) {
            $product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $_SESSION['cart'][$productId] = [
                'name' => $product['Nazwa_Produktu'],
                'price' => $product['Cena'],
                'quantity' => $quantity
            ];
        }
    }
}

// Usuwanie produktu z koszyka
if (isset($_GET['remove'])) {
    $productId = (int)$_GET['remove'];
    unset($_SESSION['cart'][$productId]);
}

// Aktualizacja ilości produktów w koszyku
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $productId => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
    }
}

// Sprawdzenie, czy koszyk jest pusty
$cartEmpty = empty($_SESSION['cart']);
$totalPrice = $cartEmpty ? 0 : calculateTotal($_SESSION['cart']);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Koszyk</h1>
        <a href="index.php">Powrót do sklepu</a>
    </header>

    <main>
        <?php if ($cartEmpty): ?>
            <p>Twój koszyk jest pusty.</p>
        <?php else: ?>
            <form action="" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Nazwa Produktu</th>
                            <th>Cena</th>
                            <th>Ilość</th>
                            <th>Razem</th>
                            <th>Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= number_format($item['price'], 2, ',', ' ') ?> zł</td>
                                <td>
                                    <input type="number" name="quantity[<?= $productId ?>]" value="<?= $item['quantity'] ?>" min="1">
                                </td>
                                <td><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> zł</td>
                                <td>
                                    <a href="?remove=<?= $productId ?>">Usuń</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" name="update">Aktualizuj Koszyk</button>
            </form>

            <div class="total">
                <p>Łączna cena: <?= number_format($totalPrice, 2, ',', ' ') ?> zł</p>
                <a href="checkout.php">Przejdź do kasy</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
