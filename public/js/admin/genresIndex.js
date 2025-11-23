// Admin Genres Index - Delete Confirmation
document.addEventListener("DOMContentLoaded", function () {
    const deleteForms = document.querySelectorAll(
        'form[action*="/admin/genres/"]'
    );

    deleteForms.forEach((form) => {
        // Check if form method is DELETE (typically contains _method input)
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput && methodInput.value === "DELETE") {
            form.addEventListener("submit", function (e) {
                if (!confirm("Hapus genre ini?")) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
});
