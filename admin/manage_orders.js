
"use strict";

/* =========================================================
   KATE'S GOODIES - MANAGE ORDERS JAVASCRIPT
   SMOOTH MOBILE SIDEBAR
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       ELEMENTS
    ===================================================== */

    const sidebar = document.getElementById("sidebar");
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const menuIcon = document.getElementById("menuIcon");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    const sidebarLinks = document.querySelectorAll(".sidebar-link");

    const logoutBtn = document.getElementById("logoutBtn");
    const logoutModal = document.getElementById("logoutModal");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    const mobileBreakpoint = 768;

    let sidebarIsOpen = false;

    /* =====================================================
       CHECK ELEMENTS
    ===================================================== */

    if (!sidebar || !mobileMenuBtn || !sidebarOverlay) {
        return;
    }

    /* =====================================================
       CURRENT PAGE
    ===================================================== */

    function setActivePage() {

        const currentPage =
            window.location.pathname.split("/").pop();

        sidebarLinks.forEach(function (link) {

            const page = link.getAttribute("data-page");

            if (page === currentPage) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }

        });

    }

    setActivePage();

    /* =====================================================
       UPDATE MENU ICON
    ===================================================== */

    function updateMenuIcon() {

        if (!menuIcon) {
            return;
        }

        if (sidebarIsOpen) {

            menuIcon.classList.remove("fa-bars");
            menuIcon.classList.add("fa-xmark");

            mobileMenuBtn.setAttribute(
                "aria-label",
                "Close navigation"
            );

            mobileMenuBtn.setAttribute(
                "aria-expanded",
                "true"
            );

        } else {

            menuIcon.classList.remove("fa-xmark");
            menuIcon.classList.add("fa-bars");

            mobileMenuBtn.setAttribute(
                "aria-label",
                "Open navigation"
            );

            mobileMenuBtn.setAttribute(
                "aria-expanded",
                "false"
            );
        }

    }

    /* =====================================================
       OPEN SIDEBAR
    ===================================================== */

    function openMobileSidebar() {

        if (window.innerWidth > mobileBreakpoint) {
            return;
        }

        sidebarIsOpen = true;

        sidebar.classList.add("mobile-open");
        sidebarOverlay.classList.add("active");

        sidebarOverlay.setAttribute(
            "aria-hidden",
            "false"
        );

        document.body.classList.add("menu-open");

        updateMenuIcon();

    }

    /* =====================================================
       CLOSE SIDEBAR
    ===================================================== */

    function closeMobileSidebar() {

        sidebarIsOpen = false;

        sidebar.classList.remove("mobile-open");
        sidebarOverlay.classList.remove("active");

        sidebarOverlay.setAttribute(
            "aria-hidden",
            "true"
        );

        document.body.classList.remove("menu-open");

        updateMenuIcon();

    }

    /* =====================================================
       TOGGLE SIDEBAR
    ===================================================== */

    function toggleMobileSidebar() {

        if (sidebarIsOpen) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }

    }

    /* =====================================================
       MENU BUTTON
    ===================================================== */

    mobileMenuBtn.addEventListener(
        "click",
        toggleMobileSidebar
    );

    /* =====================================================
       OVERLAY CLICK
    ===================================================== */

    sidebarOverlay.addEventListener(
        "click",
        closeMobileSidebar
    );

    /* =====================================================
       NAVIGATION CLICK
    ===================================================== */

    sidebarLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            if (window.innerWidth <= mobileBreakpoint) {
                closeMobileSidebar();
            }

        });

    });

    /* =====================================================
       LOGOUT MODAL
    ===================================================== */

    function openLogoutModal() {

        if (!logoutModal) {
            return;
        }

        closeMobileSidebar();

        logoutModal.classList.add("active");

        document.body.classList.add("menu-open");

    }

    function closeLogoutModal() {

        if (!logoutModal) {
            return;
        }

        logoutModal.classList.remove("active");

        document.body.classList.remove("menu-open");

    }

    if (logoutBtn) {

        logoutBtn.addEventListener(
            "click",
            openLogoutModal
        );

    }

    if (cancelLogout) {

        cancelLogout.addEventListener(
            "click",
            closeLogoutModal
        );

    }

    if (confirmLogout) {

        confirmLogout.addEventListener(
            "click",
            function () {
                window.location.href = "logout.php";
            }
        );

    }

    /* =====================================================
       CLICK OUTSIDE LOGOUT MODAL
    ===================================================== */

    if (logoutModal) {

        logoutModal.addEventListener(
            "click",
            function (event) {

                if (event.target === logoutModal) {
                    closeLogoutModal();
                }

            }
        );

    }

    /* =====================================================
       ESCAPE KEY
    ===================================================== */

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Escape") {

                if (sidebarIsOpen) {
                    closeMobileSidebar();
                }

                if (
                    logoutModal &&
                    logoutModal.classList.contains("active")
                ) {
                    closeLogoutModal();
                }

            }

        }
    );

    /* =====================================================
       WINDOW RESIZE
    ===================================================== */

    window.addEventListener(
        "resize",
        function () {

            if (window.innerWidth > mobileBreakpoint) {
                closeMobileSidebar();
            }

        }
    );

    /* =====================================================
       INITIAL STATE
    ===================================================== */

    updateMenuIcon();

});