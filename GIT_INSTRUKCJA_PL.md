# 📝 Git - Instrukcja po polsku

## ✅ Konfiguracja została ustawiona!

Git jest teraz skonfigurowany do pracy po polsku z polskimi znakami.

---

## 🎯 Jak robić commity po polsku

### **Podstawowy commit:**
```bash
git add .
git commit -m "Tytuł commita po polsku"
```

### **Commit z opisem:**
```bash
git add .
git commit -m "Tytuł commita" -m "Szczegółowy opis
- Dodano nową funkcję
- Zmieniono istniejący kod
- Naprawiono błąd z polskimi znakami"
```

### **Commit z edytorem (automatyczny szablon):**
```bash
git add .
git commit
```

Otworzy się edytor z szablonem po polsku:
```
# Tytuł commita (krótki opis, max 50 znaków)


# Szczegółowy opis zmian (opcjonalnie, max 72 znaki na linię)
# 
# Użyj polskich znaków: ą, ć, ę, ł, ń, ó, ś, ź, ż
#
# Szablon commita po polsku:
# - Dodano: nowa funkcjonalność
# - Zmieniono: modyfikacja istniejącej funkcjonalności  
# - Naprawiono: naprawa błędu
# - Usunięto: usunięcie kodu/funkcjonalności
# - Zaktualizowano: aktualizacja zależności/dokumentacji
```

---

## 📋 Przykłady commitów po polsku

### **Przykład 1: Nowa funkcja**
```bash
git commit -m "Dodanie walidacji formularza produktów

- Dodano walidację pól email i telefon
- Dodano sprawdzanie formatu EAN (8-13 cyfr)
- Dodano sanityzację HTML w opisach produktów
- Zabezpieczenie przed XSS i SQL injection"
```

### **Przykład 2: Naprawa błędu**
```bash
git commit -m "Naprawienie błędu z polskimi znakami w opisach

- Zmieniono kodowanie na UTF-8 w całej aplikacji
- Dodano konwersję polskich znaków na HTML entities
- Naprawiono wyświetlanie ą, ć, ę, ł, ń, ó, ś, ź, ż
- Zaktualizowano testy dla polskich znaków"
```

### **Przykład 3: Refaktoryzacja**
```bash
git commit -m "Refaktoryzacja kodu bezpieczeństwa

- Przeniesiono funkcje CSRF do includes/security.php
- Usunięto duplikację kodu validateCSRFToken()
- Zmieniono strukturę katalogów (config/, includes/, assets/)
- Poprawiono dokumentację i komentarze w kodzie"
```

### **Przykład 4: Aktualizacja**
```bash
git commit -m "Aktualizacja dokumentacji i zależności

- Zaktualizowano README.md z instrukcjami instalacji
- Dodano CHANGELOG.md z historią zmian
- Zaktualizowano wersję do 2.0.0
- Dodano composer.json dla zarządzania zależnościami"
```

---

## 🔧 Przydatne komendy Git

### **Sprawdzenie statusu:**
```bash
git status              # Status zmian
git log --oneline -10   # Ostatnie 10 commitów
git diff                # Różnice w plikach
```

### **Dodawanie plików:**
```bash
git add plik.php        # Dodaj konkretny plik
git add .               # Dodaj wszystkie zmiany
git add -u              # Dodaj tylko zmodyfikowane pliki
```

### **Commity:**
```bash
git commit -m "Opis"                    # Szybki commit
git commit -am "Opis"                   # Commit wszystkich zmian
git commit --amend                      # Edytuj ostatni commit
git commit --amend -m "Nowy opis"       # Zmień opis ostatniego commita
```

### **Push do GitHub:**
```bash
git push                    # Wyślij zmiany
git push origin main        # Wyślij do main
git push --force           # Wymuś push (nadpisz zdalne)
```

### **Pull z GitHub:**
```bash
git pull                    # Pobierz zmiany
git pull --rebase          # Pobierz i rebase
```

### **Cofanie zmian:**
```bash
git reset HEAD~1            # Cofnij ostatni commit (zachowaj zmiany)
git reset --hard HEAD~1     # Cofnij ostatni commit (usuń zmiany)
git reset --hard abc123     # Wróć do konkretnego commita
```

---

## 🌍 Ustawienia językowe Git

### **Obecne ustawienia (już skonfigurowane):**
```bash
git config --global i18n.commitEncoding utf-8          # ✅ UTF-8 dla commitów
git config --global i18n.logOutputEncoding utf-8       # ✅ UTF-8 dla logów
git config --global commit.template ~/.gitmessage      # ✅ Szablon po polsku
git config --global core.editor vim                    # ✅ Edytor
```

### **Sprawdzenie ustawień:**
```bash
git config --list | grep i18n
git config --list | grep commit
```

---

## 📝 Dobre praktyki commitów po polsku

### **✅ Dobre:**
```
Dodanie walidacji formularza
Naprawienie błędu z polskimi znakami
Refaktoryzacja kodu bezpieczeństwa
Aktualizacja dokumentacji README
Usunięcie niepotrzebnych plików
```

### **❌ Złe:**
```
fix                     # Zbyt ogólne
zmiany                  # Niejasne
update                  # Po angielsku
aaa                     # Bezsensowne
```

### **Struktura dobrego commita:**
```
Tytuł (50 znaków) - co zostało zrobione

Szczegóły (72 znaki/linia):
- Dlaczego zmiany były potrzebne
- Co zostało zmienione
- Jak to wpływa na aplikację
- Dodatkowe uwagi
```

---

## 🎨 Emotikony w commitach (opcjonalnie)

```
✨ Nowa funkcja
🐛 Naprawa błędu
♻️ Refaktoryzacja
📝 Dokumentacja
🔒 Bezpieczeństwo
⚡ Wydajność
💄 UI/UX
🚀 Wdrożenie
🔧 Konfiguracja
🗑️ Usunięcie
```

### **Przykład:**
```bash
git commit -m "🔒 Dodanie sanityzacji HTML w opisach produktów

- Dodano funkcję sanitizeHtml() 
- Usuwanie niebezpiecznych tagów <script>, <iframe>
- Ochrona przed Stored XSS"
```

---

## ✅ Wszystko gotowe!

Teraz gdy będziesz robił commit:
1. Git będzie używał UTF-8 dla polskich znaków (ą, ć, ę, ł, ń, ó, ś, ź, ż)
2. Gdy użyjesz `git commit` (bez -m), otworzy się szablon po polsku
3. Możesz swobodnie używać polskich znaków w commitach

**Przykład użycia:**
```bash
# Zrób zmiany w plikach
nano products.php

# Dodaj do staging
git add products.php

# Commit po polsku
git commit -m "Naprawienie błędu z walidacją EAN

- Zmieniono regex na /^\d{8,13}$/
- Dodano obsługę EAN-8 i EAN-13
- Zaktualizowano komunikaty błędów"

# Wyślij do GitHub
git push
```

**Wszystko skonfigurowane!** 🎉
