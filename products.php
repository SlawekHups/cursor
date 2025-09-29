<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Sprawdzenie konfiguracji
$config_path = $_SERVER['DOCUMENT_ROOT'] . '/app/config/parameters.php';
if (!file_exists($config_path)) {
    die("Błąd: Brak pliku konfiguracji bazy danych.");
}

$parameters = include($config_path);

// Bezpieczne określenie statusu produktu na podstawie parametru URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$allowed_filters = ['all', 'active', 'inactive'];

if (!in_array($filter, $allowed_filters)) {
    $filter = 'all';
}

// Mapowanie filtrów na statusy
$filter_config = [
    'all' => ['status' => '0,1', 'title' => 'Wszystkie', 'class' => 'btn-primary'],
    'active' => ['status' => '1', 'title' => 'Aktywne', 'class' => 'btn-success'],
    'inactive' => ['status' => '0', 'title' => 'Nieaktywne', 'class' => 'btn-danger']
];

$current_config = $filter_config[$filter];
$activeStatus = $current_config['status'];
$page_title = $current_config['title'];

// Ustawienia języka
$id_lang = 2;

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

// Pobranie ID produktu z URL (jeśli istnieje)
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

// POPRAWIONE ZAPYTANIE SQL - zabezpieczenie przed SQL injection
$sql = "SELECT p.id_product, p.ean13, p.reference, ps.wholesale_price, p.price, sa.quantity,
               COALESCE(pl.name, 'Brak nazwy') AS product_name, 
               COALESCE(pl.description, '') AS description,
               COALESCE(pl.description_short, '') AS description_short,
               img.id_image 
        FROM `" . $parameters['parameters']['database_prefix'] . "product` p
        LEFT JOIN `" . $parameters['parameters']['database_prefix'] . "product_lang` pl 
        ON p.id_product = pl.id_product AND pl.id_lang = ?
        JOIN `" . $parameters['parameters']['database_prefix'] . "product_shop` ps 
        ON p.id_product = ps.id_product
        LEFT JOIN `" . $parameters['parameters']['database_prefix'] . "image` img 
        ON p.id_product = img.id_product AND img.cover = 1
        LEFT JOIN `" . $parameters['parameters']['database_prefix'] . "stock_available` sa
        ON p.id_product = sa.id_product";

// Zabezpieczenie SQL - dodanie warunków WHERE w bezpieczny sposób
if ($filter === 'active') {
    $sql .= " WHERE p.active = 1";
} elseif ($filter === 'inactive') {
    $sql .= " WHERE p.active = 0";
} else {
    $sql .= " WHERE p.active IN (0, 1)";
}

$sql .= " GROUP BY p.id_product ORDER BY p.id_product ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_lang);
$stmt->execute();
$result = $stmt->get_result();

// Funkcja do generowania ścieżki do zdjęcia
function getImagePath($id_image) {
    if (!$id_image) {
        return "/img/p/no-image.jpg"; // Domyślne zdjęcie, jeśli brak zdjęcia
    }
    
    // PrestaShop przechowuje zdjęcia w katalogach wg struktury ID_IMAGE
    $image_path = implode("/", str_split($id_image)) . "/$id_image.jpg";
    return "/img/p/$image_path";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zmiana danych produktu - <?= htmlspecialchars($page_title) ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
</head>
<body>

    <div class="container mt-5">
        <h2>Zmień dane produktu - <?= htmlspecialchars($page_title) ?></h2>

        <!-- Przyciski filtrowania -->
        <div class="mb-5">
            <?php foreach ($filter_config as $key => $config): ?>
                <button type="button" 
                        class="btn <?= $filter === $key ? $config['class'] . ' active' : 'btn-outline-secondary' ?> filter-btn" 
                        onclick="window.location.href='products.php?filter=<?= $key ?>'">
                    <?= htmlspecialchars($config['title']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Komunikat sukcesu -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div id="success-message" class="alert alert-success" role="alert">
                Produkt został pomyślnie zaktualizowany!
            </div>
            <script>setTimeout(() => document.getElementById("success-message").style.display = "none", 2000);</script>
        <?php endif; ?>

        <!-- Komunikat błędu -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php
                switch ($_GET['error']) {
                    case 'invalid_id':
                        echo 'Nieprawidłowe ID produktu.';
                        break;
                    case 'no_data':
                        echo 'Brak danych do aktualizacji.';
                        break;
                    case 'validation':
                        echo isset($_GET['message']) ? htmlspecialchars(urldecode($_GET['message'])) : 'Błąd walidacji danych.';
                        break;
                    default:
                        echo 'Wystąpił błąd podczas aktualizacji.';
                }
                ?>
            </div>
        <?php endif; ?>

        <form id="update-form" action="update_product.php" method="post">
            
            <!-- SELECT + ZDJĘCIE W JEDNYM RZĘDZIE -->
            <div class="select-image-container">
                <select id="product_id" name="product_id" class="form-control form-control-sm" required onchange="updateFormFields()">
                    <option value="">-- Wybierz produkt --</option>
                    <?php while ($row = $result->fetch_assoc()): 
                        $imagePath = getImagePath($row['id_image']);
                        $selected = ($product_id == $row['id_product']) ? 'selected' : '';
                    ?>
                        <option value="<?= $row['id_product']; ?>" 
                                data-name="<?= htmlspecialchars($row['product_name']); ?>"
                                data-ean="<?= htmlspecialchars($row['ean13']); ?>"
                                data-reference="<?= htmlspecialchars($row['reference']); ?>"
                                data-price="<?= htmlspecialchars($row['wholesale_price']); ?>"
                                data-retail-price="<?= htmlspecialchars($row['price']); ?>"
                                data-quantity="<?= htmlspecialchars($row['quantity']); ?>"
                                data-description="<?= htmlspecialchars($row['description']); ?>"
                                data-description_short="<?= htmlspecialchars($row['description_short']); ?>"
                                data-image="<?= htmlspecialchars($imagePath); ?>"
                                <?= $selected; ?>>
                                [<?= $row['id_product']; ?>] - [<?= htmlspecialchars($row['reference']); ?>] <?= htmlspecialchars($row['product_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <img id="product-image" class="product-image" src="/img/p/no-image.jpg" alt="Zdjęcie produktu">
            </div>
            
            <!-- UKRYTE POLE PRZECHOWUJĄCE ID PRODUKTU -->
            <input type="hidden" id="hidden_product_id" name="hidden_product_id" value="<?= $product_id ?>">

            <!-- POLA EDYCJI PRODUKTU -->
            <div class="form-inline-group">
                <div class="form-group">
                    <label for="new_name">Nazwa:</label>
                    <input type="text" id="new_name" name="new_name" class="form-control" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="new_ean">EAN:</label>
                    <input type="text" id="new_ean" name="new_ean" class="form-control" maxlength="13" pattern="[0-9]{13}">
                </div>

                <div class="form-group">
                    <label for="new_reference">Indeks:</label>
                    <input type="text" id="new_reference" name="new_reference" class="form-control" maxlength="64">
                </div>

                <div class="form-group">
                    <label for="new_price">Cena hurtowa:</label>
                    <input type="number" id="new_price" name="new_price" class="form-control" min="0" max="99999.99" step="0.01">
                </div>

                <div class="form-group">
                    <label for="new_retail_price">Cena detaliczna:</label>
                    <input type="number" id="new_retail_price" name="new_retail_price" class="form-control" min="0" max="99999.99" step="0.01">
                </div>

                <div class="form-group">
                    <label for="new_quantity">Ilość w magazynie:</label>
                    <input type="number" id="new_quantity" name="new_quantity" class="form-control" min="0" max="999999" step="1">
                </div>
            </div>

            <!-- EDYTORY MARKDOWN -->
            <div class="editor-container">
                <div class="form-group">
                    <label for="new_description">Opis:</label>
                    <textarea id="new_description" name="new_description"></textarea>
                </div>

                <div class="form-group">
                    <label for="new_description_short">Krótki opis:</label>
                    <textarea id="new_description_short" name="new_description_short"></textarea>
                </div>
            </div>

            <input type="submit" value="Zmień dane" class="btn btn-primary mt-3">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        let easyMDE1 = new EasyMDE({ element: document.getElementById("new_description") });
        let easyMDE2 = new EasyMDE({ element: document.getElementById("new_description_short") });

        function updateFormFields() {
            let selectedOption = document.getElementById("product_id").selectedOptions[0];
            
            if (!selectedOption || !selectedOption.value) {
                return;
            }

            document.getElementById("new_name").value = selectedOption.getAttribute("data-name") || '';
            document.getElementById("new_ean").value = selectedOption.getAttribute("data-ean") || '';
            document.getElementById("new_reference").value = selectedOption.getAttribute("data-reference") || '';
            
            // Formatowanie ceny hurtowej (wholesale_price) i detalicznej (price) do 2 miejsc po przecinku
            const wholesalePrice = selectedOption.getAttribute("data-price");
            const retailPrice = selectedOption.getAttribute("data-retail-price");
            
            document.getElementById("new_price").value = wholesalePrice ? Number(wholesalePrice).toFixed(2) : '';
            document.getElementById("new_retail_price").value = retailPrice ? Number(retailPrice).toFixed(2) : '';
            document.getElementById("new_quantity").value = selectedOption.getAttribute("data-quantity") || '';
            document.getElementById("product-image").src = selectedOption.getAttribute("data-image") || '/img/p/no-image.jpg';

            easyMDE1.value(selectedOption.getAttribute("data-description") || '');
            easyMDE2.value(selectedOption.getAttribute("data-description_short") || '');
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Jeśli produkt jest już wybrany z URL, zaktualizuj pola
            if (document.getElementById("product_id").value) {
                updateFormFields();
            }
        });
    </script>

</body>
</html>
