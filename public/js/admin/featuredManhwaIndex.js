// Admin Featured Manhwa Index - Delete Confirmation
document.addEventListener("DOMContentLoaded", function () {
    const deleteForms = document.querySelectorAll(
        'form[action*="/admin/featured-manhwa/"]'
    );

    deleteForms.forEach((form) => {
        // Check if form method is DELETE
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput && methodInput.value === "DELETE") {
            form.addEventListener("submit", function (e) {
                if (!confirm("Yakin ingin menghapus?")) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
});
