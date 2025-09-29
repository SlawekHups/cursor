<?php
session_start();

// Włączenie raportowania błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sprawdzenie, czy plik konfiguracji istnieje
$config_path = $_SERVER['DOCUMENT_ROOT'] . '/app/config/parameters.php';
if (!file_exists($config_path)) {
    die("Błąd: Brak pliku konfiguracji bazy danych.");
}

$parameters = include($config_path);

// Funkcja walidacji danych wejściowych
function validateInput($data) {
    $errors = [];
    
    // Walidacja nazwy produktu
    if (isset($data['new_name']) && !empty($data['new_name'])) {
        if (strlen($data['new_name']) > 255) {
            $errors[] = "Nazwa produktu jest za długa (max 255 znaków).";
        }
    }
    
    // Walidacja EAN
    if (isset($data['new_ean']) && !empty($data['new_ean'])) {
        if (!preg_match('/^\d{13}$/', $data['new_ean'])) {
            $errors[] = "EAN musi składać się z dokładnie 13 cyfr.";
        }
    }
    
    // Walidacja indeksu
    if (isset($data['new_reference']) && !empty($data['new_reference'])) {
        if (strlen($data['new_reference']) > 64) {
            $errors[] = "Indeks jest za długi (max 64 znaki).";
        }
    }
    
    // Walidacja ceny hurtowej
    if (isset($data['new_price']) && $data['new_price'] !== '') {
        $price = floatval($data['new_price']);
        if ($price < 0 || $price > 99999.99) {
            $errors[] = "Cena hurtowa musi być między 0 a 99999.99.";
        }
    }
    
    // Walidacja ceny detalicznej
    if (isset($data['new_retail_price']) && $data['new_retail_price'] !== '') {
        $price = floatval($data['new_retail_price']);
        if ($price < 0 || $price > 99999.99) {
            $errors[] = "Cena detaliczna musi być między 0 a 99999.99.";
        }
    }
    
    // Walidacja ilości
    if (isset($data['new_quantity']) && $data['new_quantity'] !== '') {
        $quantity = intval($data['new_quantity']);
        if ($quantity < 0 || $quantity > 999999) {
            $errors[] = "Ilość musi być między 0 a 999999.";
        }
    }
    
    return $errors;
}

// Połączenie z bazą danych
$conn = new mysqli(
    $parameters['parameters']['database_host'],
    $parameters['parameters']['database_user'],
    $parameters['parameters']['database_password'],
    $parameters['parameters']['database_name']
);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Sprawdzenie, czy dane zostały przesłane metodą POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $id_lang = 2; // Ustawienie języka PrestaShop

    // Walidacja danych wejściowych
    $validation_errors = validateInput($_POST);
    if (!empty($validation_errors)) {
        $error_message = implode('<br>', $validation_errors);
        header("Location: products.php?error=validation&message=" . urlencode($error_message));
        exit();
    }

    if ($product_id > 0) {
        // **1. Aktualizacja `ps_product` (EAN, Indeks, Cena detaliczna)**
        if (!empty($_POST['new_ean']) || !empty($_POST['new_reference']) || $_POST['new_retail_price'] !== "") {
            $updates = [];
            $params = [];
            $types = "";

            if (!empty($_POST['new_ean'])) {
                $updates[] = "ean13 = ?";
                $params[] = $_POST['new_ean'];
                $types .= "s";
            }
            if (!empty($_POST['new_reference'])) {
                $updates[] = "reference = ?";
                $params[] = $_POST['new_reference'];
                $types .= "s";
            }
            if ($_POST['new_retail_price'] !== "") {
                $updates[] = "price = ?";
                $params[] = floatval($_POST['new_retail_price']);
                $types .= "d";
            }

            $query = "UPDATE `" . $parameters['parameters']['database_prefix'] . "product`
                      SET " . implode(", ", $updates) . " WHERE id_product = ?";
            $params[] = $product_id;
            $types .= "i";

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
        }

        // **2. Aktualizacja `ps_product_lang` (Nazwa, Opis, Krótki opis)**
        if (!empty($_POST['new_name']) || !empty($_POST['new_description']) || !empty($_POST['new_description_short'])) {
            $updates = [];
            $params = [];
            $types = "";

            if (!empty($_POST['new_name'])) {
                $updates[] = "name = ?";
                $params[] = $_POST['new_name'];
                $types .= "s";
            }
            if (isset($_POST['new_description'])) { // ✅ Teraz można zapisać pustą wartość
                $updates[] = "description = ?";
                $params[] = $_POST['new_description'] !== "" ? $_POST['new_description'] : null;
                $types .= "s";
            }
            if (isset($_POST['new_description_short'])) { // ✅ Można usunąć wartość krótkiego opisu
                $updates[] = "description_short = ?";
                $params[] = $_POST['new_description_short'] !== "" ? $_POST['new_description_short'] : null;
                $types .= "s";
            }

            $query = "UPDATE `" . $parameters['parameters']['database_prefix'] . "product_lang`
                      SET " . implode(", ", $updates) . " WHERE id_product = ? AND id_lang = ?";
            $params[] = $product_id;
            $params[] = $id_lang;
            $types .= "ii";

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
        }

        // **3. Aktualizacja `ps_product_shop` (Cena hurtowa)**
        if ($_POST['new_price'] !== "") {
            $new_price = floatval($_POST['new_price']);
            $query = "UPDATE `" . $parameters['parameters']['database_prefix'] . "product_shop`
                      SET wholesale_price = ? WHERE id_product = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("di", $new_price, $product_id);
            $stmt->execute();
            $stmt->close();
        }

        // **4. Aktualizacja `ps_stock_available` (Ilość w magazynie)**
        if ($_POST['new_quantity'] !== "") {
            $new_quantity = intval($_POST['new_quantity']);
            $query = "UPDATE `" . $parameters['parameters']['database_prefix'] . "stock_available`
                      SET quantity = ? WHERE id_product = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $new_quantity, $product_id);
            $stmt->execute();
            $stmt->close();
        }

        // **Przekierowanie do `products.php` z ID produktu i komunikatem sukcesu**
        header("Location: products.php?product_id=" . $product_id . "&success=1");
        exit();
    } else {
        header("Location: products.php?error=invalid_id");
        exit();
    }
} else {
    header("Location: products.php?error=no_data");
    exit();
}
?>