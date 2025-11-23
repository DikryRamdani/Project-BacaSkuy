// Admin Manhwa Index - Delete Confirmation
document.addEventListener("DOMContentLoaded", function () {
    // Delete confirmation
    document
        .querySelectorAll('form[action*="/admin/manhwa/"]')
        .forEach((form) => {
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput && methodInput.value === "DELETE") {
                form.addEventListener("submit", function (e) {
                    if (!confirm("Hapus manhwa ini?")) e.preventDefault();
                });
            }
        });

    // Entry animation for cards
    const cards = document.querySelectorAll("#manhwa-grid > div");
    cards.forEach((card, i) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(16px) scale(.97)";
        const delay = 40 + i * 80;
        setTimeout(() => {
            card.style.transition =
                "opacity .6s cubic-bezier(0.16,0.84,0.44,1), transform .6s cubic-bezier(0.16,0.84,0.44,1)";
            requestAnimationFrame(() => {
                card.style.opacity = "1";
                card.style.transform = "translateY(0) scale(1)";
            });
        }, delay);
    });
});
