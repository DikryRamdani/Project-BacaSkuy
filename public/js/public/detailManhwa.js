// Toast notification function
function showMessage(message, type) {
    const toast = document.createElement("div");
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg ${
        type === "success" ? "bg-green-500" : "bg-red-500"
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Initialize detail manhwa page
document.addEventListener("DOMContentLoaded", function () {
    const manhwaElement = document.querySelector("[data-manhwa-id]");
    if (!manhwaElement) return;

    const manhwaId = manhwaElement.dataset.manhwaId;
    const isAuthenticated = manhwaElement.dataset.authenticated === "true";
    let currentRating = 0;

    // Check favorite status and load rating on page load for authenticated users
    if (isAuthenticated) {
        checkFavoriteStatus();
        loadUserRating();
    }

    // Check if manhwa is favorited
    function checkFavoriteStatus() {
        fetch(`/manhwa/${manhwaId}/favorite/check`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                updateFavoriteButton(data.isFavorited);
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }

    // Update favorite button UI
    function updateFavoriteButton(isFavorited) {
        const icon = document.getElementById("favoriteIcon");
        const btn = document.getElementById("favoriteBtn");

        if (!icon || !btn) return;

        if (isFavorited) {
            icon.className = "bi bi-heart-fill text-xl text-red-500";
            btn.title = "Remove from Favorites";
            btn.className =
                "flex items-center justify-center w-10 h-10 rounded-lg border border-red-500 text-red-500 hover:bg-red-50 transition-all";
        } else {
            icon.className = "bi bi-heart text-xl";
            btn.title = "Add to Favorites";
            btn.className =
                "flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-50 transition-all";
        }
    }

    // Toggle favorite (global function)
    window.toggleFavorite = function () {
        const btn = document.getElementById("favoriteBtn");
        if (!btn) return;

        btn.disabled = true;

        fetch(`/manhwa/${manhwaId}/favorite/toggle`, {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    updateFavoriteButton(data.isFavorited);
                    showMessage(data.message, "success");
                } else {
                    showMessage("Failed to update favorite", "error");
                    console.log("Response data:", data);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showMessage("An error occurred", "error");
            })
            .finally(() => {
                btn.disabled = false;
            });
    };

    // Load user's current rating
    function loadUserRating() {
        fetch(`/manhwa/${manhwaId}/rating/user`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.rating) {
                    currentRating = data.rating;
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }

    // Open rating modal (global function)
    window.openRatingModal = function () {
        const modal = document.getElementById("ratingModal");
        if (!modal) return;

        modal.classList.remove("hidden");
        modal.classList.add("flex");

        // Pre-select current rating if exists
        if (currentRating > 0) {
            selectRating(currentRating);
        }
    };

    // Close rating modal (global function)
    window.closeRatingModal = function () {
        const modal = document.getElementById("ratingModal");
        if (!modal) return;

        modal.classList.add("hidden");
        modal.classList.remove("flex");

        // Reset rating selection
        resetRatingStars();
    };

    // Select rating (global function)
    window.selectRating = function (rating) {
        currentRating = rating;
        const selectedRatingElement = document.getElementById("selectedRating");
        const submitBtn = document.getElementById("submitRatingBtn");

        if (selectedRatingElement) {
            selectedRatingElement.textContent = rating;
        }
        if (submitBtn) {
            submitBtn.disabled = false;
        }

        // Update star colors
        const stars = document.querySelectorAll(".rating-star");
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove("text-gray-300");
                star.classList.add("text-yellow-500");
            } else {
                star.classList.add("text-gray-300");
                star.classList.remove("text-yellow-500");
            }
        });
    };

    // Reset rating stars
    function resetRatingStars() {
        currentRating = 0;
        const selectedRatingElement = document.getElementById("selectedRating");
        const submitBtn = document.getElementById("submitRatingBtn");

        if (selectedRatingElement) {
            selectedRatingElement.textContent = "0";
        }
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        const stars = document.querySelectorAll(".rating-star");
        stars.forEach((star) => {
            star.classList.add("text-gray-300");
            star.classList.remove("text-yellow-500");
        });
    }

    // Submit rating (global function)
    window.submitRating = function () {
        if (currentRating === 0) {
            showMessage("Please select a rating", "error");
            return;
        }

        const btn = document.getElementById("submitRatingBtn");
        if (!btn) return;

        btn.disabled = true;
        btn.textContent = "Submitting...";

        fetch(`/manhwa/${manhwaId}/rating`, {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                rating: currentRating,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Update average rating display
                    const avgRatingElement =
                        document.getElementById("averageRating");
                    const totalRatingsElement =
                        document.getElementById("totalRatings");

                    if (avgRatingElement) {
                        avgRatingElement.textContent = parseFloat(
                            data.averageRating
                        ).toFixed(1);
                    }
                    if (totalRatingsElement) {
                        totalRatingsElement.textContent = data.totalRatings;
                    }

                    showMessage(data.message, "success");
                    window.closeRatingModal();
                } else {
                    showMessage("Failed to submit rating", "error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showMessage("An error occurred", "error");
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = "Submit Rating";
            });
    };

    // Close modal when clicking outside
    const ratingModal = document.getElementById("ratingModal");
    if (ratingModal) {
        ratingModal.addEventListener("click", function (e) {
            if (e.target === this) {
                window.closeRatingModal();
            }
        });
    }
});
