
document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    const logoutBtn = document.getElementById("logoutBtn");
    const logoutModal = document.getElementById("logoutModal");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    const sidebarLinks = document.querySelectorAll(".sidebar-link");

    /*
    |--------------------------------------------------------------------------
    | SIDEBAR FUNCTIONS
    |--------------------------------------------------------------------------
    */

    function openSidebar() {
        if (!sidebar || !sidebarOverlay || !mobileMenuBtn) {
            return;
        }

        sidebar.classList.add("open");
        sidebarOverlay.classList.add("show");

        mobileMenuBtn.classList.add("active");
        mobileMenuBtn.setAttribute("aria-expanded", "true");
        mobileMenuBtn.setAttribute("aria-label", "Close navigation");

        // Keep the hamburger icon visible.
        const icon = mobileMenuBtn.querySelector("i");

        if (icon) {
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars");
        }

        document.body.classList.add("sidebar-open");
        updateBodyScroll();
    }

    function closeSidebar() {
        if (!sidebar || !sidebarOverlay || !mobileMenuBtn) {
            return;
        }

        sidebar.classList.remove("open");
        sidebarOverlay.classList.remove("show");

        mobileMenuBtn.classList.remove("active");
        mobileMenuBtn.setAttribute("aria-expanded", "false");
        mobileMenuBtn.setAttribute("aria-label", "Open navigation");

        // Keep the hamburger icon visible.
        const icon = mobileMenuBtn.querySelector("i");

        if (icon) {
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars");
        }

        document.body.classList.remove("sidebar-open");
        updateBodyScroll();
    }

    function toggleSidebar() {
        if (!sidebar) {
            return;
        }

        if (sidebar.classList.contains("open")) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MOBILE MENU BUTTON
    |--------------------------------------------------------------------------
    */

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener("click", toggleSidebar);
    }

    /*
    |--------------------------------------------------------------------------
    | SIDEBAR OVERLAY
    |--------------------------------------------------------------------------
    */

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", closeSidebar);
    }

    /*
    |--------------------------------------------------------------------------
    | SIDEBAR LINKS
    |--------------------------------------------------------------------------
    */

    sidebarLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            closeSidebar();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | LOGOUT MODAL
    |--------------------------------------------------------------------------
    */

    function openLogoutModal() {
        if (!logoutModal) {
            return;
        }

        logoutModal.classList.add("show");
        logoutModal.setAttribute("aria-hidden", "false");

        document.body.classList.add("modal-open");
        updateBodyScroll();

        if (cancelLogout) {
            cancelLogout.focus();
        }
    }

    function closeLogoutModal() {
        if (!logoutModal) {
            return;
        }

        logoutModal.classList.remove("show");
        logoutModal.setAttribute("aria-hidden", "true");

        document.body.classList.remove("modal-open");
        updateBodyScroll();
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT BUTTON
    |--------------------------------------------------------------------------
    */

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function () {
            closeSidebar();
            openLogoutModal();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL LOGOUT
    |--------------------------------------------------------------------------
    */

    if (cancelLogout) {
        cancelLogout.addEventListener("click", function () {
            closeLogoutModal();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRM LOGOUT
    |--------------------------------------------------------------------------
    */

    if (confirmLogout) {
        confirmLogout.addEventListener("click", function () {
            // Redirect to the PHP logout script.
            window.location.assign("logout.php");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CLOSE MODAL WHEN CLICKING OUTSIDE
    |--------------------------------------------------------------------------
    */

    if (logoutModal) {
        logoutModal.addEventListener("click", function (event) {
            if (event.target === logoutModal) {
                closeLogoutModal();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ESCAPE KEY
    |--------------------------------------------------------------------------
    */

    document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") {
            return;
        }

        if (
            logoutModal &&
            logoutModal.classList.contains("show")
        ) {
            closeLogoutModal();
            return;
        }

        closeSidebar();
    });

    /*
    |--------------------------------------------------------------------------
    | WINDOW RESIZE
    |--------------------------------------------------------------------------
    */

    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | PREVENT BACKGROUND SCROLLING
    |--------------------------------------------------------------------------
    */

    function updateBodyScroll() {
        const sidebarIsOpen =
            sidebar && sidebar.classList.contains("open");

        const modalIsOpen =
            logoutModal && logoutModal.classList.contains("show");

        if (sidebarIsOpen || modalIsOpen) {
            document.body.classList.add("no-scroll");
        } else {
            document.body.classList.remove("no-scroll");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INITIAL STATE
    |--------------------------------------------------------------------------
    */

    closeSidebar();
    closeLogoutModal();
});