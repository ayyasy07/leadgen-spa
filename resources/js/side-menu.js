(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".side-menu").forEach(function (menu) {
            menu.addEventListener("click", function (e) {
                var parent = menu.parentElement;
                var subMenu = parent.querySelector("ul");

                if (!subMenu) return; // gak ada submenu, lanjut navigasi normal

                e.preventDefault();

                var isOpen = subMenu.classList.contains("side-menu__sub-open");
                var icon = menu.querySelector(".side-menu__sub-icon");

                if (isOpen) {
                    // Tutup
                    subMenu.style.height = subMenu.scrollHeight + "px";
                    requestAnimationFrame(function () {
                        subMenu.style.transition = "height 300ms ease";
                        subMenu.style.height = "0px";
                    });
                    subMenu.classList.remove("side-menu__sub-open");
                    if (icon) icon.classList.remove("transform", "rotate-180");
                    menu.classList.remove("side-menu--open");
                } else {
                    // Buka
                    subMenu.style.height = "0px";
                    subMenu.style.overflow = "hidden";
                    subMenu.style.transition = "height 300ms ease";
                    requestAnimationFrame(function () {
                        subMenu.style.height = subMenu.scrollHeight + "px";
                    });
                    subMenu.classList.add("side-menu__sub-open");
                    if (icon) icon.classList.add("transform", "rotate-180");
                    menu.classList.add("side-menu--open");
                }
            });
        });
    });
})();