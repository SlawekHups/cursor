<?php
/**
 * Test CSRF Protection
 * Ten plik testuje czy zabezpieczenie CSRF działa poprawnie
 */

session_start();

echo "<h2>🔒 Test zabezpieczeń CSRF</h2>";

// Test 1: Sprawdź czy token jest generowany
if (isset($_SESSION['csrf_token'])) {
    echo "✅ <strong>Test 1 PASSED:</strong> Token CSRF został wygenerowany<br>";
    echo "📝 Token: " . substr($_SESSION['csrf_token'], 0, 16) . "...<br><br>";
} else {
    echo "❌ <strong>Test 1 FAILED:</strong> Brak tokenu CSRF w sesji<br><br>";
}

// Test 2: Symulacja ataku CSRF (bez tokenu)
echo "<h3>Test 2: Próba ataku bez tokenu CSRF</h3>";
echo "<form method='post' action='update_product.php'>";
echo "<input type='hidden' name='product_id' value='1'>";
echo "<input type='hidden' name='new_name' value='HACKED PRODUCT'>";
echo "<input type='submit' value='🚨 Próba ataku (bez CSRF)' class='btn btn-danger'>";
echo "</form><br>";

// Test 3: Prawidłowe żądanie z tokenem
echo "<h3>Test 3: Prawidłowe żądanie z tokenem CSRF</h3>";
echo "<form method='post' action='update_product.php'>";
echo "<input type='hidden' name='product_id' value='1'>";
echo "<input type='hidden' name='new_name' value='Test Product'>";
if (isset($_SESSION['csrf_token'])) {
    echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
}
echo "<input type='submit' value='✅ Prawidłowe żądanie (z CSRF)' class='btn btn-success'>";
echo "</form><br>";

// Test 4: Błędny token
echo "<h3>Test 4: Żądanie z błędnym tokenem CSRF</h3>";
echo "<form method='post' action='update_product.php'>";
echo "<input type='hidden' name='product_id' value='1'>";
echo "<input type='hidden' name='new_name' value='Test Product'>";
echo "<input type='hidden' name='csrf_token' value='fake_token_12345'>";
echo "<input type='submit' value='⚠️ Błędny token (powinien zostać zablokowany)' class='btn btn-warning'>";
echo "</form><br>";

echo "<hr>";
echo "<h3>🔙 Powrót do aplikacji</h3>";
echo "<a href='products.php' class='btn btn-primary'>← Wróć do zarządzania produktami</a>";

// Dodaj style Bootstrap dla lepszego wyglądu
echo "
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .btn { padding: 8px 12px; margin: 5px; text-decoration: none; border: none; border-radius: 4px; cursor: pointer; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-success { background-color: #28a745; color: white; }
    .btn-warning { background-color: #ffc107; color: black; }
    .btn-primary { background-color: #007bff; color: white; }
    form { margin: 10px 0; }
</style>
";
?>
