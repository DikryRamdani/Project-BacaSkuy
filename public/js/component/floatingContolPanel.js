document.addEventListener("DOMContentLoaded", function () {
    const fitBtn = document.getElementById("fit-btn");
    const actualBtn = document.getElementById("actual-btn");
    const reader = document.getElementById("reader");
    const imgs = Array.from(document.querySelectorAll(".reader-img"));
    const containers = Array.from(document.querySelectorAll(".page-container"));

    function setFit() {
        if (!reader) return;

        // Hapus gap antar page
        reader.classList.remove("space-y-6");
        reader.classList.add("space-y-0");

        // Hapus padding & rounded dari container
        containers.forEach((c) => {
            c.classList.remove("p-4", "rounded");
            c.classList.add("p-0");
        });

        // Set gambar fit width
        imgs.forEach((i) => {
            i.style.width = "100%";
            i.style.height = "auto";
            i.style.display = "block";
        });

        // Update button states
        fitBtn.classList.add("bg-blue-600", "text-white", "shadow-blue-500/50");
        fitBtn.classList.remove("bg-white");
        actualBtn.classList.remove(
            "bg-blue-600",
            "text-white",
            "shadow-blue-500/50"
        );
        actualBtn.classList.add("bg-white");
    }

    function setActual() {
        if (!reader) return;

        // Kembalikan gap antar page
        reader.classList.remove("space-y-0");
        reader.classList.add("space-y-6");

        // Kembalikan padding & rounded
        containers.forEach((c) => {
            c.classList.remove("p-0");
            c.classList.add("p-4", "rounded");
        });

        // Reset gambar ke ukuran asli
        imgs.forEach((i) => {
            i.style.width = "";
            i.style.height = "";
            i.style.display = "";
        });

        // Update button states
        actualBtn.classList.add(
            "bg-blue-600",
            "text-white",
            "shadow-blue-500/50"
        );
        actualBtn.classList.remove("bg-white");
        fitBtn.classList.remove(
            "bg-blue-600",
            "text-white",
            "shadow-blue-500/50"
        );
        fitBtn.classList.add("bg-white");
    }

    if (fitBtn) fitBtn.addEventListener("click", setFit);
    if (actualBtn) actualBtn.addEventListener("click", setActual);

    // Scroll buttons
    const topBtn = document.getElementById("top-btn");
    const bottomBtn = document.getElementById("bottom-btn");

    if (topBtn) {
        topBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        });
    }

    if (bottomBtn) {
        bottomBtn.addEventListener("click", () => {
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: "smooth",
            });
        });
    }

    // Toggle panel visibility saat klik di mana saja pada layar
    const floatingPanel = document.getElementById("floating-panel");
    let panelVisible = false;

    if (floatingPanel) {
        document.addEventListener("click", (e) => {
            // Jangan toggle jika klik pada link/button di dalam panel
            if (
                e.target.closest("#floating-panel a") ||
                e.target.closest("#floating-panel button")
            ) {
                return;
            }

            panelVisible = !panelVisible;
            if (panelVisible) {
                floatingPanel.classList.remove(
                    "opacity-0",
                    "pointer-events-none"
                );
                floatingPanel.classList.add(
                    "opacity-100",
                    "pointer-events-auto"
                );
            } else {
                floatingPanel.classList.remove(
                    "opacity-100",
                    "pointer-events-auto"
                );
                floatingPanel.classList.add("opacity-0", "pointer-events-none");
            }
        });
    } // Set default ke Fit Width
    setFit();
});
