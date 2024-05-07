document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("excelLink").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("excelForm").submit();
    });

    document.getElementById("excelButton").addEventListener("click", function(event) {
        document.getElementById("excelForm").submit();
    });
});
