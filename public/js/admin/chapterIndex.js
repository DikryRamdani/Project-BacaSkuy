// Admin Chapter Index - Bulk Delete, Toggle, and Delete Confirm
// Bulk delete & selection
(function () {
    const selectAll = document.getElementById("select-all");
    const checkboxes = Array.from(document.querySelectorAll(".row-checkbox"));
    const bulkBtn = document.getElementById("bulk-delete-btn");
    const selectedCount = document.getElementById("selected-count");
    const bulkForm = document.getElementById("bulk-delete-form");

    function updateState() {
        const checked = checkboxes
            .filter((cb) => cb.checked)
            .map((cb) => cb.value);
        if (selectedCount)
            selectedCount.textContent = checked.length
                ? checked.length + " terpilih"
                : "";
        if (bulkBtn) bulkBtn.disabled = checked.length === 0;
    }

    if (selectAll) {
        selectAll.addEventListener("change", function () {
            checkboxes.forEach((cb) => (cb.checked = this.checked));
            updateState();
        });
    }
    checkboxes.forEach((cb) => cb.addEventListener("change", updateState));

    if (bulkBtn) {
        bulkBtn.addEventListener("click", function () {
            const ids = checkboxes
                .filter((cb) => cb.checked)
                .map((cb) => cb.value);
            if (!ids.length) return;
            if (!confirm("Hapus " + ids.length + " chapter terpilih?")) return;
            Array.from(
                bulkForm.querySelectorAll('input[name="ids[]"]')
            ).forEach((n) => n.remove());
            ids.forEach((id) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "ids[]";
                input.value = id;
                bulkForm.appendChild(input);
            });
            bulkForm.submit();
        });
    }
})();

// Per-row delete confirmation & toggle chapter groups
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".delete-form").forEach((form) => {
        form.addEventListener("submit", (e) => {
            if (!confirm("Hapus chapter ini?")) e.preventDefault();
        });
    });

    document.querySelectorAll(".toggle-chapters").forEach((header) => {
        header.addEventListener("click", () => {
            const manhwaId = header.dataset.manhwaId;
            const content = document.querySelector(
                `.chapters-content[data-manhwa-id="${manhwaId}"]`
            );
            const icon = header.querySelector(".toggle-icon i");
            if (!content || !icon) return;
            const pTag = header.querySelector("p");
            const coverWrapper = header.querySelector(".w-16");
            const opening = content.classList.contains("hidden");
            if (opening) {
                content.classList.remove("hidden");
                icon.classList.replace("bi-chevron-right", "bi-chevron-down");
                header.classList.remove(
                    "bg-white",
                    "text-gray-800",
                    "hover:bg-gray-50"
                );
                header.classList.add(
                    "bg-indigo-600",
                    "text-white",
                    "hover:bg-indigo-700"
                );
                if (pTag) {
                    pTag.classList.remove("text-gray-600");
                    pTag.classList.add("text-indigo-100");
                }
                if (coverWrapper) {
                    coverWrapper.classList.remove("bg-gray-100");
                    coverWrapper.classList.add("bg-white/10");
                }
            } else {
                content.classList.add("hidden");
                icon.classList.replace("bi-chevron-down", "bi-chevron-right");
                header.classList.remove(
                    "bg-indigo-600",
                    "text-white",
                    "hover:bg-indigo-700"
                );
                header.classList.add(
                    "bg-white",
                    "text-gray-800",
                    "hover:bg-gray-50"
                );
                if (pTag) {
                    pTag.classList.remove("text-indigo-100");
                    pTag.classList.add("text-gray-600");
                }
                if (coverWrapper) {
                    coverWrapper.classList.remove("bg-white/10");
                    coverWrapper.classList.add("bg-gray-100");
                }
            }
        });
    });
});
