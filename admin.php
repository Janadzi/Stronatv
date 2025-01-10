<?php
// Lista tabel w projekcie
$tabele = [
    "Produkty" => "produkty.php",
    "Zamówienia" => "zamowienia.php",
    "Klienci" => "klienci.php",
    "Kategorie" => "kategorie.php",
    "Pracownicy" => "pracownicy.php",
    "Dostawcy" => "dostawcy.php",
    "Magazyn" => "magazyn.php",
    "Faktury" => "faktury.php",
    "Koszyki" => "koszyk.php",
    "Kategorie produktów" => "kategorie.php"
];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
        }
        main {
            margin: 20px auto;
            max-width: 800px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 1.8em;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin: 10px 0;
        }
        ul li a {
            text-decoration: none;
            color: #007bff;
            font-size: 1.2em;
            padding: 10px 15px;
            display: inline-block;
            border: 1px solid #007bff;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        ul li a:hover {
            background-color: #007bff;
            color: #fff;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel Administratora</h1>
    </header>
    <main>
        <h2>Wybierz tabelę do zarządzania</h2>
        <ul>
            <?php foreach ($tabele as $nazwaTabeli => $plik): ?>
                <li><a href="<?= $plik ?>"><?= $nazwaTabeli ?></a></li>
            <?php endforeach; ?>
        </ul>
    </main>
    <footer>
        © <?= date("Y") ?> Twoja Firma - Wszelkie prawa zastrzeżone
    </footer>
</body>
</html>
