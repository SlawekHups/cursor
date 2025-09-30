<?php
/**
 * Generator PDF dla produktów PrestaShop - Format A4
 */

error_reporting(0);
ini_set('display_errors', 0);

$config_path = $_SERVER['DOCUMENT_ROOT'] . '/app/config/parameters.php';
if (!file_exists($config_path)) {
    die("Błąd: Brak pliku konfiguracji bazy danych.");
}

$parameters = include($config_path);
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    die("Błąd: Nieprawidłowe ID produktu.");
}

$conn = new mysqli(
    $parameters['parameters']['database_host'],
    $parameters['parameters']['database_user'],
    $parameters['parameters']['database_password'],
    $parameters['parameters']['database_name']
);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych.");
}

$id_lang = 2;

$sql = "SELECT p.id_product, p.ean13, p.reference, ps.wholesale_price, p.price, sa.quantity,
               COALESCE(pl.name, 'Brak nazwy') AS product_name, 
               COALESCE(pl.description, '') AS description,
               COALESCE(pl.description_short, '') AS description_short,
               img.id_image,
               p.active
        FROM `" . $parameters['parameters']['database_prefix'] . "product` p
        LEFT JOIN `" . $parameters['parameters']['database_prefix'] . "product_lang` pl 
        ON p.id_product = pl.id_product AND pl.id_lang = ?
        JOIN `" . $parameters['parameters']['database_prefix'] . "product_shop` ps 
        ON p.id_product = ps.id_product
        LEFT JOIN `" . $parameters['parameters']['database_prefix'] . "image` img 
        ON p.id_product = img.id_product AND img.cover = 1
        LEFT JOIN `" . $parameters['parameters']['database_prefix'] . "stock_available` sa
        ON p.id_product = sa.id_product
        WHERE p.id_product = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_lang, $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Błąd: Produkt nie został znaleziony.");
}

$image_url = '/img/p/' . $product['id_image'] . '.jpg';
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Karta Produktu - <?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        @page { size: A4; margin: 20mm; }
        @media print { body { margin: 0; padding: 0; } .no-print { display: none !important; } }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.6; color: #333; background: white; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20mm; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #333; }
        .header h1 { font-size: 24pt; font-weight: bold; color: #2c3e50; margin-bottom: 10px; }
        .product-title { font-size: 18pt; font-weight: bold; color: #34495e; margin: 15px 0; padding: 10px; background: #ecf0f1; border-left: 5px solid #3498db; }
        .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .image-section { text-align: center; }
        .product-image { max-width: 100%; max-height: 250px; border: 2px solid #ddd; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .data-section { padding: 10px; }
        .data-row { display: flex; margin-bottom: 10px; padding: 8px; background: #f8f9fa; border-radius: 5px; }
        .data-row:nth-child(even) { background: #e9ecef; }
        .data-label { font-weight: bold; width: 140px; color: #2c3e50; }
        .data-value { flex: 1; color: #34495e; }
        .section-title { font-size: 14pt; font-weight: bold; color: white; margin: 20px 0 10px 0; padding: 8px; background: #3498db; border-radius: 5px; }
        .description { padding: 15px; background: #f8f9fa; border-left: 4px solid #3498db; border-radius: 5px; margin: 10px 0; }
        .footer { position: fixed; bottom: 10mm; left: 20mm; right: 20mm; text-align: center; font-size: 9pt; color: #7f8c8d; border-top: 1px solid #ddd; padding-top: 10px; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 10pt; }
        .status-active { background: #27ae60; color: white; }
        .status-inactive { background: #e74c3c; color: white; }
        .price-highlight { font-size: 14pt; font-weight: bold; color: #27ae60; }
        .print-button { position: fixed; top: 20px; right: 20px; padding: 15px 30px; background: #3498db; color: white; border: none; border-radius: 5px; font-size: 14pt; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .print-button:hover { background: #2980b9; }
        .back-button { position: fixed; top: 20px; right: 200px; padding: 15px 30px; background: #95a5a6; color: white; border: none; border-radius: 5px; font-size: 14pt; cursor: pointer; text-decoration: none; display: inline-block; }
        .back-button:hover { background: #7f8c8d; }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Drukuj / Zapisz PDF</button>
    <a href="products.php" class="back-button no-print">← Powrót</a>
    
    <div class="pdf-container">
        <div class="header">
            <h1>KARTA PRODUKTU</h1>
        </div>
        
        <div class="product-title">
            <?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        
        <div class="content-grid">
            <div class="image-section">
                <?php if ($product['id_image']): ?>
                    <img src="<?= $image_url ?>" alt="Produkt" class="product-image">
                <?php else: ?>
                    <div style="padding: 50px; background: #ecf0f1; border: 2px dashed #bdc3c7;">
                        <p style="text-align: center; color: #7f8c8d;">Brak zdjęcia</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="data-section">
                <div class="data-row">
                    <div class="data-label">ID Produktu:</div>
                    <div class="data-value"><?= $product['id_product'] ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">EAN:</div>
                    <div class="data-value"><?= htmlspecialchars($product['ean13'] ?: 'Brak', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Referencja:</div>
                    <div class="data-value"><?= htmlspecialchars($product['reference'] ?: 'Brak', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Status:</div>
                    <div class="data-value">
                        <span class="status-badge <?= $product['active'] ? 'status-active' : 'status-inactive' ?>">
                            <?= $product['active'] ? '✅ Aktywny' : '❌ Nieaktywny' ?>
                        </span>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-label">Cena hurtowa:</div>
                    <div class="data-value price-highlight"><?= number_format($product['wholesale_price'], 2, ',', ' ') ?> zł</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Cena detaliczna:</div>
                    <div class="data-value price-highlight"><?= number_format($product['price'], 2, ',', ' ') ?> zł</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Ilość:</div>
                    <div class="data-value"><strong><?= $product['quantity'] ?> szt.</strong></div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($product['description_short'])): ?>
        <div class="section-title">📝 KRÓTKI OPIS</div>
        <div class="description">
            <?= $product['description_short'] ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($product['description'])): ?>
        <div class="section-title">📄 PEŁNY OPIS</div>
        <div class="description">
            <?= $product['description'] ?>
        </div>
        <?php endif; ?>
        
        <div class="footer">
            <p>Wygenerowano: <?= date('Y-m-d H:i:s') ?> | PrestaShop Product Manager | ID: <?= $product['id_product'] ?></p>
        </div>
    </div>
    
    <script>
        document.querySelector('.print-button').addEventListener('click', function() {
            setTimeout(function() {
                alert('💡 TIP: Wybierz "Zapisz jako PDF" w oknie druku aby zapisać do pliku PDF.');
            }, 100);
        });
    </script>
</body>
</html>
