document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener("click", e => {
            const targetSelector = link.getAttribute("href");
            if (!targetSelector || targetSelector.length <= 1) return;

            const target = document.querySelector(targetSelector);

            // === Fancybox ===
            if (targetSelector === "#main-form") {
                e.preventDefault();

                if (typeof Fancybox !== "undefined") {
                    Fancybox.show([
                        {
                            src: targetSelector, // ← исправлено
                            type: "inline"
                        }
                    ]);
                }

                return;
            }

            // === Скролл ===
            if (target) {
                e.preventDefault();
                smoothScrollToElement(targetSelector, 800);
            }
        });
    });

});