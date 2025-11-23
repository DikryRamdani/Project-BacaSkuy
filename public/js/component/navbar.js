// Navbar Component JavaScript
// Live search suggestions
(function () {
    const input = document.getElementById("search-input");
    const box = document.getElementById("search-suggestions");
    if (!input || !box) return;

    let timer = null;
    input.addEventListener("input", function (e) {
        const q = this.value.trim();
        clearTimeout(timer);
        if (q.length < 1) {
            box.classList.add("hidden");
            box.innerHTML = "";
            return;
        }
        timer = setTimeout(() => fetchSuggestions(q), 220);
    });

    input.addEventListener("blur", function () {
        // small delay so clicks register
        setTimeout(() => {
            box.classList.add("hidden");
        }, 180);
    });

    input.addEventListener("focus", function () {
        if (box.innerHTML) box.classList.remove("hidden");
    });

    // Jika user menekan Enter, navigasi ke halaman home dengan query q
    input.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            const q = this.value.trim();
            if (q.length === 0) return;
            const home = window.routes?.home || "/";
            window.location.href = home + "?q=" + encodeURIComponent(q);
        }
    });

    // Tombol search juga trigger navigasi
    const btn = document.getElementById("search-btn");
    if (btn) {
        btn.addEventListener("click", function () {
            const q = input.value.trim();
            if (!q) return;
            const home = window.routes?.home || "/";
            window.location.href = home + "?q=" + encodeURIComponent(q);
        });
    }

    function fetchSuggestions(q) {
        const searchSuggestRoute =
            window.routes?.searchSuggest || "/search/suggest";
        fetch(searchSuggestRoute + "?q=" + encodeURIComponent(q))
            .then((r) => r.json())
            .then(render)
            .catch(() => {
                box.classList.add("hidden");
            });
    }

    function render(items) {
        if (!items || !items.length) {
            box.innerHTML =
                '<div class="p-3 text-sm text-gray-500">Tidak ada hasil</div>';
            box.classList.remove("hidden");
            return;
        }
        box.innerHTML = items
            .map((i) => {
                const thumb = i.cover
                    ? `<img src="${i.cover}" class="w-12 h-16 object-cover rounded">`
                    : `<div class="w-12 h-16 bg-gray-200 rounded"></div>`;
                return `
                <a href="/manhwa/${
                    i.slug
                }" class="flex items-center gap-3 p-3 hover:bg-gray-100 border-b border-gray-100">
                    <div class="shrink-0">${thumb}</div>
                    <div class="flex-1">
                        <div class="font-medium">${escapeHtml(i.title)}</div>
                        <div class="text-xs text-gray-500">${escapeHtml(
                            i.author || ""
                        )}</div>
                    </div>
                </a>
            `;
            })
            .join("");
        box.classList.remove("hidden");
    }

    function escapeHtml(text) {
        return String(text).replace(/[&<>"']/g, function (s) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
            }[s];
        });
    }
})();

// Profile menu toggle and accessibility
(function () {
    const btn = document.getElementById("profile-btn");
    const menu = document.getElementById("profile-menu");
    if (!btn || !menu) return;

    function openMenu() {
        menu.classList.remove("hidden");
        btn.setAttribute("aria-expanded", "true");
        // fokus ke first actionable item
        const first = menu.querySelector("a, button");
        if (first) first.focus();
    }

    function closeMenu() {
        menu.classList.add("hidden");
        btn.setAttribute("aria-expanded", "false");
    }

    btn.addEventListener("click", function (e) {
        e.stopPropagation();
        if (menu.classList.contains("hidden")) openMenu();
        else closeMenu();
    });

    // Close when clicking outside
    document.addEventListener("click", function (e) {
        if (!menu.contains(e.target) && !btn.contains(e.target)) closeMenu();
    });

    // Close on Escape
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeMenu();
    });

    // Prevent blur closing when interacting with menu
    menu.addEventListener("click", function (e) {
        e.stopPropagation();
    });
})();

// Mobile search functionality
(function () {
    const mobileSearchBtn = document.getElementById("mobile-search-btn");
    const mobileSearchModal = document.getElementById("mobile-search-modal");
    const closeMobileSearch = document.getElementById("close-mobile-search");
    const mobileSearchInput = document.getElementById("mobile-search-input");
    const mobileSearchResults = document.getElementById(
        "mobile-search-results"
    );

    if (!mobileSearchBtn || !mobileSearchModal) return;

    let timer = null;

    mobileSearchBtn.addEventListener("click", function () {
        mobileSearchModal.classList.remove("hidden");
        mobileSearchInput.focus();
    });

    closeMobileSearch.addEventListener("click", function () {
        mobileSearchModal.classList.add("hidden");
        mobileSearchInput.value = "";
        mobileSearchResults.innerHTML = "";
    });

    mobileSearchInput.addEventListener("input", function () {
        const q = this.value.trim();
        clearTimeout(timer);
        if (q.length < 1) {
            mobileSearchResults.innerHTML = "";
            return;
        }
        timer = setTimeout(() => fetchMobileSuggestions(q), 220);
    });

    mobileSearchInput.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            const q = this.value.trim();
            if (q.length === 0) return;
            const home = window.routes?.home || "/";
            window.location.href = home + "?q=" + encodeURIComponent(q);
        }
    });

    function fetchMobileSuggestions(q) {
        const searchSuggestRoute =
            window.routes?.searchSuggest || "/search/suggest";
        fetch(searchSuggestRoute + "?q=" + encodeURIComponent(q))
            .then((r) => r.json())
            .then(renderMobile)
            .catch(() => {
                mobileSearchResults.innerHTML = "";
            });
    }

    function renderMobile(items) {
        if (!items || !items.length) {
            mobileSearchResults.innerHTML =
                '<div class="p-3 text-sm text-gray-500">Tidak ada hasil</div>';
            return;
        }
        mobileSearchResults.innerHTML = items
            .map((i) => {
                const thumb = i.cover
                    ? `<img src="${i.cover}" class="w-12 h-16 object-cover rounded">`
                    : `<div class="w-12 h-16 bg-gray-200 rounded"></div>`;
                return `
                <a href="/manhwa/${
                    i.slug
                }" class="flex items-center gap-3 p-3 hover:bg-gray-100 border-b border-gray-100">
                    <div class="shrink-0">${thumb}</div>
                    <div class="flex-1">
                        <div class="font-medium">${escapeHtml(i.title)}</div>
                        <div class="text-xs text-gray-500">${escapeHtml(
                            i.author || ""
                        )}</div>
                    </div>
                </a>
            `;
            })
            .join("");
    }

    function escapeHtml(text) {
        return String(text).replace(/[&<>"']/g, function (s) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
            }[s];
        });
    }
})();
