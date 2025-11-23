// Get CSRF token
const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

// Upload profile image
function uploadProfileImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size (2MB max)
    if (file.size > 2 * 1024 * 1024) {
        showToast("Ukuran file maksimal 2MB", "error");
        return;
    }

    // Validate file type
    if (!file.type.startsWith("image/")) {
        showToast("File harus berupa gambar", "error");
        return;
    }

    const formData = new FormData();
    formData.append("profile_image", file);

    // Show loading state
    const preview = document.getElementById("profile-image-preview");
    const originalContent = preview.innerHTML;
    preview.innerHTML =
        '<div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>';

    fetch("/settings/profile-picture", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
            Accept: "application/json",
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update preview
                preview.innerHTML = `<img src="${data.imageUrl}" alt="Profile" class="w-full h-full object-cover">`;
                showToast(data.message, "success");

                // Refresh page after 1 second to update navbar
                setTimeout(() => window.location.reload(), 1000);
            } else {
                preview.innerHTML = originalContent;
                showToast(data.message || "Gagal mengupload foto", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            preview.innerHTML = originalContent;
            showToast("Terjadi kesalahan saat mengupload foto", "error");
        });
}

// Remove profile image
function removeProfileImage() {
    if (!confirm("Hapus foto profil?")) return;

    fetch("/settings/profile-picture", {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": token,
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast(data.message, "success");
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || "Gagal menghapus foto", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan", "error");
        });
}

// Update username
document
    .getElementById("username-form")
    .addEventListener("submit", function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value.trim();

        if (name.length < 3) {
            showToast("Username minimal 3 karakter", "error");
            return;
        }

        fetch("/settings/username", {
            method: "PUT",
            headers: {
                "X-CSRF-TOKEN": token,
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ name }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast(data.message, "success");
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(
                        data.message || "Gagal memperbarui username",
                        "error"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Terjadi kesalahan", "error");
            });
    });

// Update email
document.getElementById("email-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();

    if (!email || !email.includes("@")) {
        showToast("Email tidak valid", "error");
        return;
    }

    fetch("/settings/email", {
        method: "PUT",
        headers: {
            "X-CSRF-TOKEN": token,
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ email }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast(data.message, "success");
            } else {
                showToast(data.message || "Gagal memperbarui email", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan", "error");
        });
});

// Update password
document
    .getElementById("password-form")
    .addEventListener("submit", function (e) {
        e.preventDefault();

        const currentPassword =
            document.getElementById("current_password").value;
        const newPassword = document.getElementById("new_password").value;
        const newPasswordConfirmation = document.getElementById(
            "new_password_confirmation"
        ).value;

        if (newPassword.length < 8) {
            showToast("Password baru minimal 8 karakter", "error");
            return;
        }

        if (newPassword !== newPasswordConfirmation) {
            showToast("Konfirmasi password tidak sesuai", "error");
            return;
        }

        fetch("/settings/password", {
            method: "PUT",
            headers: {
                "X-CSRF-TOKEN": token,
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: newPasswordConfirmation,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast(data.message, "success");
                    // Reset form
                    document.getElementById("password-form").reset();
                } else {
                    showToast(
                        data.message || "Gagal memperbarui password",
                        "error"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Terjadi kesalahan", "error");
            });
    });

// Confirm delete account
function confirmDeleteAccount() {
    if (
        confirm(
            "Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan."
        )
    ) {
        if (confirm("Terakhir kali! Anda yakin ingin menghapus akun Anda?")) {
            showToast("Fitur hapus akun akan segera tersedia", "info");
            // TODO: Implement delete account functionality
        }
    }
}

// Toast notification function
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
