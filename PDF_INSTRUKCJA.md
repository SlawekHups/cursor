# 📄 Generator PDF dla Produktów PrestaShop

## 📋 Opis

Generator PDF umożliwia tworzenie profesjonalnych kart produktów w formacie A4 ze wszystkimi danymi i zdjęciem.

---

## ✨ Funkcje

### 🎯 Zawartość karty produktu:
- ✅ **Nagłówek** - "KARTA PRODUKTU"
- ✅ **Nazwa produktu** - duży, wyróżniony tytuł
- ✅ **Zdjęcie produktu** - jeśli dostępne (max 250px wysokości)
- ✅ **Dane podstawowe:**
  - ID Produktu
  - EAN (kod kreskowy)
  - Referencja (indeks)
  - Status (Aktywny/Nieaktywny)
- ✅ **Ceny:**
  - Cena hurtowa (wyróżniona zielonym)
  - Cena detaliczna (wyróżniona zielonym)
- ✅ **Magazyn:**
  - Ilość w magazynie
- ✅ **Opisy:**
  - Krótki opis (jeśli istnieje)
  - Pełny opis (jeśli istnieje)
- ✅ **Stopka:**
  - Data wygenerowania
  - ID produktu

---

## 🚀 Użycie

### **Metoda 1: Z formularza products.php**
1. Otwórz `products.php`
2. Wybierz produkt z listy
3. Kliknij przycisk **"📄 Generuj PDF"**
4. Otworzy się nowe okno z kartą produktu
5. Kliknij **"🖨️ Drukuj / Zapisz PDF"**
6. Wybierz **"Zapisz jako PDF"** w oknie druku

### **Metoda 2: Bezpośredni link**
```
http://your-domain.com/generate_pdf.php?id=123
```
Gdzie `123` to ID produktu.

---

## 📐 Format PDF

### **Wymiary:**
- **Format:** A4 (210mm × 297mm)
- **Orientacja:** Pionowa (Portrait)
- **Marginesy:** 20mm ze wszystkich stron

### **Layout:**
```
┌─────────────────────────────────────────────────┐
│              KARTA PRODUKTU                     │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│                                                 │
│  Nazwa Produktu (duży tytuł)                   │
│                                                 │
│  ┌─────────────┐  ┌─────────────────────────┐ │
│  │             │  │ ID: 123                  │ │
│  │   ZDJĘCIE   │  │ EAN: 1234567890123       │ │
│  │   PRODUKTU  │  │ Referencja: ABC-001      │ │
│  │             │  │ Status: ✅ Aktywny        │ │
│  │             │  │ Cena hurtowa: 99.99 zł   │ │
│  │             │  │ Cena detaliczna: 149.99  │ │
│  └─────────────┘  │ Ilość: 50 szt.           │ │
│                   └─────────────────────────┘ │
│                                                 │
│  📝 KRÓTKI OPIS                                │
│  [Treść krótkiego opisu...]                   │
│                                                 │
│  📄 PEŁNY OPIS                                 │
│  [Treść pełnego opisu...]                     │
│                                                 │
│                                                 │
│─────────────────────────────────────────────────│
│  Wygenerowano: 2024-12-19 | ID: 123            │
└─────────────────────────────────────────────────┘
```

---

## 🎨 Style i Formatowanie

### **Kolory:**
- **Nagłówek:** #2c3e50 (ciemny niebieski)
- **Tytuł produktu:** #34495e (szary-niebieski)
- **Sekcje:** #3498db (niebieski)
- **Ceny:** #27ae60 (zielony)
- **Status aktywny:** #27ae60 (zielony)
- **Status nieaktywny:** #e74c3c (czerwony)

### **Czcionki:**
- **Nagłówek:** 24pt, pogrubiony
- **Tytuł produktu:** 18pt, pogrubiony
- **Tytuły sekcji:** 14pt, pogrubiony
- **Dane:** 11pt, normalny
- **Opisy:** 10pt, normalny

---

## 💻 Jak to działa?

### **Technologia:**
- ✅ **HTML + CSS** - Szablon karty produktu
- ✅ **CSS @page** - Format A4, marginesy
- ✅ **window.print()** - Funkcja druku przeglądarki
- ✅ **@media print** - Style dla druku

### **Proces generowania:**
1. Użytkownik klika "Generuj PDF"
2. Otwiera się `generate_pdf.php?id=XXX`
3. Skrypt pobiera dane produktu z bazy
4. Generuje HTML ze stylami A4
5. Użytkownik klika "Drukuj / Zapisz PDF"
6. Przeglądarka generuje PDF z HTML

### **Zalety:**
- ✅ Brak zewnętrznych bibliotek
- ✅ Działa w każdej przeglądarce
- ✅ Pełna kontrola nad wyglądem
- ✅ Obsługa polskich znaków
- ✅ Responsywny HTML
- ✅ Możliwość edycji CSS

---

## 🧪 Testowanie

### **Test 1: Podstawowy**
1. Otwórz `products.php`
2. Wybierz produkt
3. Kliknij "📄 Generuj PDF"
4. Sprawdź czy dane się wyświetlają

### **Test 2: Zapisywanie PDF**
1. W oknie karty produktu kliknij "🖨️ Drukuj / Zapisz PDF"
2. W oknie druku wybierz "Zapisz jako PDF"
3. Wybierz lokalizację i zapisz
4. Otwórz PDF i sprawdź formatowanie

### **Test 3: Różne produkty**
- Produkt ze zdjęciem
- Produkt bez zdjęcia
- Produkt z długim opisem
- Produkt bez opisów

---

## 🎨 Customizacja

### **Zmiana kolorów:**
```css
/* W generate_pdf.php, sekcja <style> */
.header h1 { color: #YOUR_COLOR; }
.section-title { background: #YOUR_COLOR; }
.price-highlight { color: #YOUR_COLOR; }
```

### **Zmiana układu:**
```css
/* Szerokość kolumn */
.content-grid { grid-template-columns: 40% 60%; }

/* Wysokość zdjęcia */
.product-image { max-height: 300px; }
```

### **Dodanie logo firmy:**
```html
<!-- W generate_pdf.php, w <div class="header"> -->
<img src="/img/logo.png" alt="Logo" style="max-width: 150px;">
<h1>KARTA PRODUKTU</h1>
```

---

## 🐛 Rozwiązywanie problemów

### **Problem: PDF nie generuje się**
**Rozwiązanie:** 
- Sprawdź czy produkt istnieje w bazie
- Sprawdź uprawnienia do generate_pdf.php

### **Problem: Brak zdjęcia w PDF**
**Rozwiązanie:**
- Sprawdź ścieżkę: `/img/p/{id_image}.jpg`
- Sprawdź czy zdjęcie istnieje w katalogu

### **Problem: Polskie znaki nie wyświetlają się**
**Rozwiązanie:**
- Przeglądarka powinna automatycznie obsłużyć UTF-8
- Upewnij się że baza danych ma utf8mb4

### **Problem: Formatowanie się rozjeżdża**
**Rozwiązanie:**
- Użyj Chrome/Edge do generowania PDF
- Dostosuj CSS w sekcji `@media print`

---

## 📊 Kompatybilność przeglądarek

| Przeglądarka | Generowanie PDF | Jakość |
|--------------|----------------|--------|
| **Chrome** | ✅ Doskonałe | ⭐⭐⭐⭐⭐ |
| **Edge** | ✅ Doskonałe | ⭐⭐⭐⭐⭐ |
| **Firefox** | ✅ Dobre | ⭐⭐⭐⭐ |
| **Safari** | ✅ Dobre | ⭐⭐⭐⭐ |

**Zalecane:** Chrome lub Edge dla najlepszej jakości PDF

---

## 🔧 Pliki

| Plik | Opis |
|------|------|
| **generate_pdf.php** | Generator PDF - główny plik |
| **products.php** | Przycisk "Generuj PDF" + funkcja JS |

---

## ✅ Gotowe do użycia!

Generator PDF jest w pełni funkcjonalny i gotowy do użycia:
- ✅ Format A4
- ✅ Wszystkie dane produktu
- ✅ Zdjęcie produktu
- ✅ Profesjonalny wygląd
- ✅ Polskie znaki
- ✅ Możliwość zapisania jako PDF

**Przetestuj:** Wybierz produkt i kliknij "📄 Generuj PDF"! 🚀
