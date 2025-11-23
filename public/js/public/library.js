function removeFavorite(manhwaId, manhwaSlug) {
    if (!confirm("Hapus manhwa ini dari favorit?")) {
        return;
    }

    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
    if (!token) {
        showToast("Error: CSRF token not found", "error");
        return;
    }

    fetch(`/manhwa/${manhwaSlug}/favorite/toggle`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
            Accept: "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then((data) => {
            if (data.isFavorited === false) {
                showToast("Dihapus dari favorit", "success");
                // Reload page after short delay to update the list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Gagal menghapus favorit", "error");
        });
}

function showToast(message, type = "info") {
    const container = document.getElementById("toast-container");
    if (!container) return;

    const toast = document.createElement("div");
    const bgColor =
        type === "success"
            ? "bg-green-500"
            : type === "error"
            ? "bg-red-500"
            : "bg-blue-500";

    toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-2 transition-opacity duration-300 flex items-center gap-2`;

    const icon = type === "success" ? "✓" : type === "error" ? "✕" : "ℹ";
    toast.innerHTML = `<span class="font-bold">${icon}</span><span>${message}</span>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
