"use strict";

/* =========================================================
   KATES GOODIES ADMIN MESSAGE
   MOBILE SIDEBAR BURGER TO X FUNCTION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");

    const mobileMenuIcon = mobileMenuBtn
        ? mobileMenuBtn.querySelector("i")
        : null;

    const sidebarLinks = document.querySelectorAll(".sidebar-link");

    const MOBILE_BREAKPOINT = 768;

    if (!sidebar || !mobileMenuBtn) {
        console.warn("Sidebar elements were not found.");
        return;
    }

    /* =========================================================
       CHECK MOBILE VIEW
    ========================================================= */

    function isMobileView() {
        return window.innerWidth <= MOBILE_BREAKPOINT;
    }

    /* =========================================================
       CHANGE BURGER ICON TO X
    ========================================================= */

    function setBurgerIcon(isOpen) {
        if (!mobileMenuIcon) {
            return;
        }

        if (isOpen) {
            mobileMenuIcon.classList.remove("fa-bars");
            mobileMenuIcon.classList.add("fa-xmark");
        } else {
            mobileMenuIcon.classList.remove("fa-xmark");
            mobileMenuIcon.classList.add("fa-bars");
        }
    }

    /* =========================================================
       OPEN MOBILE SIDEBAR
    ========================================================= */

    function openMobileSidebar() {
        if (!isMobileView()) {
            return;
        }

        sidebar.classList.add("mobile-open");

        if (sidebarOverlay) {
            sidebarOverlay.classList.add("active");
        }

        mobileMenuBtn.classList.add("active");

        mobileMenuBtn.setAttribute(
            "aria-expanded",
            "true"
        );

        mobileMenuBtn.setAttribute(
            "aria-label",
            "Close navigation menu"
        );

        setBurgerIcon(true);

        document.body.classList.add("sidebar-is-open");
    }

    /* =========================================================
       CLOSE MOBILE SIDEBAR
    ========================================================= */

    function closeMobileSidebar() {
        sidebar.classList.remove("mobile-open");

        if (sidebarOverlay) {
            sidebarOverlay.classList.remove("active");
        }

        mobileMenuBtn.classList.remove("active");

        mobileMenuBtn.setAttribute(
            "aria-expanded",
            "false"
        );

        mobileMenuBtn.setAttribute(
            "aria-label",
            "Open navigation menu"
        );

        setBurgerIcon(false);

        document.body.classList.remove("sidebar-is-open");
    }

    /* =========================================================
       TOGGLE BURGER / X BUTTON
    ========================================================= */

    function toggleMobileSidebar() {
        if (
            sidebar.classList.contains("mobile-open")
        ) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    }

    /* =========================================================
       BURGER BUTTON
       THE SAME BUTTON BECOMES THE X BUTTON
    ========================================================= */

    mobileMenuBtn.setAttribute(
        "aria-expanded",
        "false"
    );

    mobileMenuBtn.setAttribute(
        "aria-label",
        "Open navigation menu"
    );

    mobileMenuBtn.addEventListener(
        "click",
        function (event) {
            event.preventDefault();
            event.stopPropagation();

            toggleMobileSidebar();
        }
    );

    /* =========================================================
       CLOSE WHEN CLICKING OUTSIDE THE SIDEBAR
    ========================================================= */

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener(
            "click",
            function () {
                closeMobileSidebar();
            }
        );
    }

    /* =========================================================
       SIDEBAR LINKS
    ========================================================= */

    sidebarLinks.forEach(function (link) {
        link.addEventListener(
            "click",
            function () {
                if (isMobileView()) {
                    closeMobileSidebar();
                }
            }
        );
    });

    /* =========================================================
       ESCAPE KEY CLOSE
    ========================================================= */

    document.addEventListener(
        "keydown",
        function (event) {
            if (event.key !== "Escape") {
                return;
            }

            if (
                sidebar.classList.contains(
                    "mobile-open"
                )
            ) {
                closeMobileSidebar();
            }
        }
    );

    /* =========================================================
       RESPONSIVE RESIZE
    ========================================================= */

    let previousWidth = window.innerWidth;
    let resizeTimer = null;

    window.addEventListener(
        "resize",
        function () {
            clearTimeout(resizeTimer);

            resizeTimer = setTimeout(
                function () {
                    const currentWidth =
                        window.innerWidth;

                    const crossedBreakpoint =
                        (
                            previousWidth >
                                MOBILE_BREAKPOINT &&
                            currentWidth <=
                                MOBILE_BREAKPOINT
                        ) ||
                        (
                            previousWidth <=
                                MOBILE_BREAKPOINT &&
                            currentWidth >
                                MOBILE_BREAKPOINT
                        );

                    if (crossedBreakpoint) {
                        closeMobileSidebar();
                    }

                    previousWidth = currentWidth;
                },
                120
            );
        }
    );

});