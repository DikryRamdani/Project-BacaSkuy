// Detail Manhwa Page - Toggle All Chapters
let chaptersExpanded = false;

function toggleAllChapters() {
    const hiddenChapters = document.querySelectorAll(".chapter-hidden");
    const btnText = document.getElementById("toggleBtnText");
    const btnIcon = document.getElementById("toggleBtnIcon");
    const chapterList = document.getElementById("chapterList");

    if (!hiddenChapters.length || !btnText || !btnIcon) return;

    const totalChapters = window.totalChapters || hiddenChapters.length + 5;

    chaptersExpanded = !chaptersExpanded;

    hiddenChapters.forEach((chapter) => {
        if (chaptersExpanded) {
            chapter.classList.remove("hidden");
            chapter.classList.add("flex");
        } else {
            chapter.classList.add("hidden");
            chapter.classList.remove("flex");
        }
    });

    if (chaptersExpanded) {
        btnText.textContent = "Sembunyikan Chapter";
        btnIcon.classList.remove("bi-chevron-down");
        btnIcon.classList.add("bi-chevron-up");

        // Smooth scroll to first hidden chapter
        setTimeout(() => {
            const firstHidden = document.querySelector(
                ".chapter-item:nth-child(11)"
            );
            if (firstHidden) {
                firstHidden.scrollIntoView({
                    behavior: "smooth",
                    block: "nearest",
                });
            }
        }, 100);
    } else {
        btnText.textContent = `Lihat Semua Chapter (${totalChapters})`;
        btnIcon.classList.remove("bi-chevron-up");
        btnIcon.classList.add("bi-chevron-down");

        // Scroll back to top of chapter list
        if (chapterList) {
            chapterList.scrollIntoView({
                behavior: "smooth",
                block: "nearest",
            });
        }
    }
}
