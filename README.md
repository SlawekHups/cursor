# System Zarządzania Produktami

## 📋 Opis projektu

System do zarządzania produktami PrestaShop z możliwością edycji podstawowych danych produktów.

## 🔧 Instalacja

1. Umieść pliki w katalogu dostępnym dla serwera web
2. Upewnij się, że plik konfiguracji PrestaShop istnieje w ścieżce: `/app/config/parameters.php`
3. Skonfiguruj uprawnienia do zapisu w bazie danych

## 📁 Struktura plików

- `products.php` - Główny interfejs zarządzania produktami
- `update_product.php` - Skrypt aktualizacji danych produktów
- `styles.css` - Style CSS interfejsu
- `scripts.js` - Skrypty JavaScript (obecnie nieużywane w nowym interfejsie)

## 🚀 Użytkowanie

### Dostęp do systemu
Otwórz w przeglądarce: `products.php`

### Filtrowanie produktów
- **Wszystkie** - `products.php?filter=all`
- **Aktywne** - `products.php?filter=active`  
- **Nieaktywne** - `products.php?filter=inactive`

### Edycja produktu
1. Wybierz produkt z listy rozwijanej
2. Pola formularza automatycznie się wypełnią
3. Wprowadź zmiany w wybranych polach
4. Kliknij "Zmień dane"

## ✅ Zabezpieczenia

### Zaimplementowane zabezpieczenia:
- ✅ **SQL Injection** - Zabezpieczenie przed atakami SQL injection
- ✅ **CSRF Protection** - Tokeny zabezpieczające przed Cross-Site Request Forgery
- ✅ **Walidacja danych** - Sprawdzanie poprawności wprowadzanych danych
- ✅ **Ograniczenia długości** - Limity dla pól tekstowych i numerycznych
- ✅ **Escape HTML** - Zabezpieczenie przed XSS w wyświetlanych danych
- ✅ **Walidacja EAN** - Sprawdzanie formatu kodu EAN (13 cyfr)

### Walidowane pola:
- **Nazwa produktu**: max 255 znaków
- **EAN**: dokładnie 13 cyfr
- **Indeks**: max 64 znaki
- **Ceny**: 0-99999.99
- **Ilość**: 0-999999

## 📊 Aktualizowane tabele

System aktualizuje następujące tabele PrestaShop:
- `ps_product` - podstawowe dane produktu (EAN, indeks, cena detaliczna)
- `ps_product_lang` - tłumaczenia (nazwa, opisy)
- `ps_product_shop` - dane sklepowe (cena hurtowa)
- `ps_stock_available` - stan magazynowy

## 🔄 Changelog

### v2.1 - CSRF Protection
- ✅ **Dodanie zabezpieczenia CSRF** - tokeny bezpieczeństwa w formularzach
- ✅ **Walidacja tokenów** - sprawdzanie autentyczności żądań
- ✅ **Test bezpieczeństwa** - plik `csrf_test.php` do testowania

### v2.0 - Refaktoryzacja
- ✅ Połączenie 3 plików w jeden uniwersalny `products.php`
- ✅ Usunięcie duplikacji kodu
- ✅ Zabezpieczenie przed SQL injection
- ✅ Dodanie walidacji danych wejściowych
- ✅ Poprawa obsługi błędów
- ✅ Naprawa błędów JavaScript

### v1.0 - Wersja początkowa
- Podstawowa funkcjonalność edycji produktów
- Oddzielne pliki dla różnych filtrów

## ⚠️ Uwagi

- System wymaga PrestaShop w wersji kompatybilnej ze strukturą bazy danych
- Przed użyciem na serwerze produkcyjnym zaleca się wyłączenie `display_errors`
- ID języka jest ustawione na 2 (można zmienić w kodzie)

## 🔍 Rozwój

## 🧪 Testowanie

### Test zabezpieczeń CSRF
Uruchom `csrf_test.php` aby przetestować:
- Generowanie tokenów CSRF
- Walidację tokenów
- Blokowanie nieprawidłowych żądań
- Przepuszczanie prawidłowych żądań

## 🔮 Planowane ulepszenia

- ~~Dodanie CSRF protection~~ ✅ **ZROBIONE**
- System uwierzytelniania użytkowników
- Logi operacji (audit trail)
- API endpoints
- Batch operations