document.addEventListener("DOMContentLoaded", function () {
    // === Мобильное меню ===
    const body = document.body;
    const menu = document.querySelector(".mobile-menu");
    const burger = document.querySelector(".menu-toggle");
    document.addEventListener("click", function (e) {
        if (burger && e.target.closest(".menu-toggle")) {
            e.stopPropagation();
            burger.classList.toggle("active");
            if (menu) menu.classList.toggle("active");
            body.classList.toggle("_fixed");
            return;
        }
        if (menu && e.target.closest(".mobile-menu .main-navigation a")) {
            if (burger) burger.classList.remove("active");
            menu.classList.remove("active");
            body.classList.remove("_fixed");
            return;
        }
        if (menu && !e.target.closest(".mobile-menu") && burger) {
            burger.classList.remove("active");
            menu.classList.remove("active");
            body.classList.remove("_fixed");
        }
    });

    // === Подменю ===
    const menuItems = document.querySelectorAll(".menu-item-has-children");
    menuItems.forEach(item => {
        const link = item.querySelector("a");
        const dropdown = item.querySelector(".dropdown-menu");
        if (!link || !dropdown) return;

        link.addEventListener("click", e => {
            const isOpen = dropdown.classList.contains("show");
            if (!isOpen) {
                e.preventDefault();
                document.querySelectorAll(".dropdown-menu.show").forEach(open => open.classList.remove("show"));
                dropdown.classList.add("show");
                body.classList.add("fixed");
            }
        });

        dropdown.addEventListener("mouseleave", () => {
            dropdown.classList.remove("show");
            if (!document.querySelector(".dropdown-menu.show")) body.classList.remove("fixed");
        });
    });

    document.addEventListener("click", e => {
        menuItems.forEach(item => {
            if (!item.contains(e.target)) {
                const dropdown = item.querySelector(".dropdown-menu");
                if (dropdown) dropdown.classList.remove("show");
            }
        });
        if (!document.querySelector(".dropdown-menu.show")) body.classList.remove("fixed");
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const buttons = document.querySelectorAll('.toggle-menu');
    const closeButtons = document.querySelectorAll('.close-menu');

    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const menu = button.nextElementSibling;

            // закрываем все остальные меню
            document.querySelectorAll('.main-navigation .active').forEach(el => {
                if (el !== menu) {
                    el.classList.remove('active');
                }
            });

            // переключаем текущее
            menu.classList.toggle('active');
        });
    });

    // клик по кнопке закрытия
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            document.querySelectorAll('.main-navigation .active').forEach(el => {
                el.classList.remove('active');
            });
        });
    });

    // клик вне меню — закрываем всё
    document.addEventListener('click', function (e) {
        const isClickInside = e.target.closest('.main-navigation');

        if (!isClickInside) {
            document.querySelectorAll('.main-navigation .active').forEach(el => {
                el.classList.remove('active');
            });
        }
    });

});