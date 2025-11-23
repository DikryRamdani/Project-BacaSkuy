// Explore Page - Toggle filters visibility
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggle-filters");
    const filters = document.getElementById("advanced-filters");
    const chevron = document.getElementById("filter-chevron");

    if (toggleBtn && filters && chevron) {
        toggleBtn.addEventListener("click", function () {
            filters.classList.toggle("hidden");
            chevron.classList.toggle("rotate-180");
        });
    }
});
