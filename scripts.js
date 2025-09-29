// Funkcja aktualizująca wyświetlaną aktualną cenę
function updatePrice() {
    var select = document.getElementById("product_id");
    var currentPriceSpan = document.getElementById("current_price");
    var selectedOption = select.options[select.selectedIndex];
    var currentPrice = selectedOption.getAttribute("data-price");
    if (currentPriceSpan) {
        currentPriceSpan.textContent = "Aktualna cena: " + currentPrice;
    }
}

// Funkcja ukrywająca komunikat o sukcesie po 2 sekundach
function hideSuccessMessage() {
    setTimeout(function () {
        var successMessage = document.getElementById("success-message");
        successMessage.style.display = "none";
    }, 2000);
}

// Wywołanie funkcji przy ładowaniu strony, aby zainicjować wyświetlaną cenę
updatePrice();

$(document).ready(function(){
    $(".filter-btn").click(function(){
        let status = $(this).data("status");
        let activeStatus;
        
        if(status === "all") {
            activeStatus = "0,1"; // wszystkie produkty: zarówno nieaktywne, jak i aktywne
        } else if(status === "1") {
            activeStatus = "1"; // tylko aktywne
        } else if(status === "0") {
            activeStatus = "0"; // tylko nieaktywne
        }
        
        // Ustawienie wartości w ukrytym polu
        $("#activeStatus").val(activeStatus);
        
        // (opcjonalnie) jeśli chcesz też przejść do innego formularza, możesz dodać przekierowanie:
        let link = $(this).data("link");
        window.location.href = link;
    });
});