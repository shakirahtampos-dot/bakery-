"use strict";

/*
 * ADMIN SIDEBAR — hamburger (open) + close(X) button (close)
 * ---------------------------------------------------------
 * UPDATED BEHAVIOR:
 * The fixed hamburger button (top-left) is now used ONLY to
 * OPEN the mobile drawer. As soon as the drawer opens, that
 * button is hidden completely — the drawer's own X button
 * (top-right, inside the sidebar) is the single, unambiguous
 * way to close it. This avoids ever showing two "close"
 * affordances at once (a morphed hamburger AND a real X).
 *
 * This version is still defensive on purpose: it does not
 * rely only on a CSS file being present/up to date on every
 * page. It drives the visible show/hide state directly via
 * inline styles as well as classes, so the button always
 * gives correct visual feedback even if this page is loading
 * a stale copy of the stylesheet (which is what was happening
 * on admin_message.php).
 *
 * COPY THIS SAME FILE (unchanged) INTO EVERY admin_*.php PAGE
 * that uses the sidebar, instead of keeping separate copies.
 * That's what caused the bug in the recording: admin_message.php
 * had its own older copy without the fixes below.
 * ---------------------------------------------------------
 */

document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const sidebarCloseBtn = document.getElementById("sidebarCloseBtn");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    const sidebarLinks = document.querySelectorAll(".sidebar-link");

    const logoutBtn = document.getElementById("logoutBtn");
    const logoutAllBtn = document.getElementById("logoutAllBtn");

    const logoutModal = document.getElementById("logoutModal");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    const darkMode = document.getElementById("darkMode");

    const messageClose = document.getElementById("messageClose");
    const messageBox = document.getElementById("messageBox");

    /* Warn loudly in the console if the page is missing pieces
       (e.g. a duplicate #sidebar id, or the close button markup
       was never added on this page). This is exactly the kind
       of silent failure that caused the recorded bug. */
    if (!sidebar) console.warn("[sidebar] #sidebar not found on this page.");
    if (!mobileMenuBtn) console.warn("[sidebar] #mobileMenuBtn not found on this page.");
    if (!sidebarCloseBtn) console.warn("[sidebar] #sidebarCloseBtn not found on this page — the drawer will have no visible close button.");
    if (!sidebarOverlay) console.warn("[sidebar] #sidebarOverlay not found on this page.");


    /* =========================================================
       SETTINGS
    ========================================================= */

    const MOBILE_BREAKPOINT = 768;


    function isMobile() {
        return window.innerWidth <= MOBILE_BREAKPOINT;
    }


    /* =========================================================
       ACTIVE PAGE
    ========================================================= */

    function setActivePage() {

        const currentPage =
            window.location.pathname
                .split("/")
                .pop()
                .split("?")[0];

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


    /* =========================================================
       HAMBURGER BUTTON VISIBILITY (fail-safe, style-driven)
       ---------------------------------------------------------
       The hamburger button only opens the drawer. Once open, it
       is hidden entirely — inline styles are used so it stays
       hidden even if this page's stylesheet is stale and lacks
       the corresponding rule.
    ========================================================= */

    function hideMenuButton() {

        if (!mobileMenuBtn) {
            return;
        }

        mobileMenuBtn.style.display = "none";
        mobileMenuBtn.setAttribute("aria-hidden", "true");
        mobileMenuBtn.tabIndex = -1;

    }


    function restoreMenuButton() {

        if (!mobileMenuBtn) {
            return;
        }

        /* Clear the inline override so CSS decides visibility again
           (flex on mobile widths, none on desktop widths). */
        mobileMenuBtn.style.display = "";
        mobileMenuBtn.removeAttribute("aria-hidden");
        mobileMenuBtn.tabIndex = 0;

    }


    /* =========================================================
       CLOSE BUTTON — GUARANTEE IT'S VISIBLE
       ---------------------------------------------------------
       If, for whatever reason, this page's CSS doesn't include
       the mobile "display: flex" override for .sidebar-close-btn,
       force it visible with inline styles once we know we're on
       a mobile-width screen. This is what fixes the "no close
       button ever appears" issue seen in the recording.
    ========================================================= */

    function ensureCloseButtonVisible() {

        if (!sidebarCloseBtn) {
            return;
        }

        if (isMobile()) {
            sidebarCloseBtn.style.display = "flex";
        } else {
            sidebarCloseBtn.style.display = "";
        }

    }


    /* =========================================================
       OPEN MOBILE SIDEBAR
    ========================================================= */

    function openMobileSidebar() {

        if (!sidebar || !isMobile()) {
            return;
        }

        sidebar.classList.add("mobile-open");

        if (sidebarOverlay) {

            sidebarOverlay.classList.add("active");
            sidebarOverlay.setAttribute("aria-hidden", "false");

        }

        /* Hamburger button disappears — the sidebar's own X
           button is now the only way to close the drawer. */
        hideMenuButton();
        ensureCloseButtonVisible();

        document.body.classList.add("sidebar-is-open");
        document.body.style.overflow = "hidden";

        /* Move focus to the close button for keyboard/screen-reader users. */
        if (sidebarCloseBtn) {
            sidebarCloseBtn.focus();
        }

    }


    /* =========================================================
       CLOSE MOBILE SIDEBAR
    ========================================================= */

    function closeMobileSidebar() {

        if (sidebar) {
            sidebar.classList.remove("mobile-open");
        }

        if (sidebarOverlay) {

            sidebarOverlay.classList.remove("active");
            sidebarOverlay.setAttribute("aria-hidden", "true");

        }

        /* Bring the hamburger button back so the drawer can be
           reopened. */
        restoreMenuButton();

        document.body.classList.remove("sidebar-is-open");

        if (!logoutModal || !logoutModal.classList.contains("active")) {
            document.body.style.overflow = "";
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.focus();
        }

    }


    /* =========================================================
       TOGGLE MOBILE SIDEBAR (with a short click-lock so fast
       repeat taps on mobile can't fire the toggle twice and
       cause the open/close "flicker" seen in the recording)
    ========================================================= */

    let toggleLocked = false;

    function toggleMobileSidebar() {

        if (!sidebar || !isMobile() || toggleLocked) {
            return;
        }

        toggleLocked = true;
        window.setTimeout(function () { toggleLocked = false; }, 250);

        if (sidebar.classList.contains("mobile-open")) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }

    }


    if (mobileMenuBtn) {

        mobileMenuBtn.addEventListener("click", function (event) {

            event.preventDefault();
            event.stopPropagation();

            toggleMobileSidebar();

        });

    }


    if (sidebarCloseBtn) {

        sidebarCloseBtn.addEventListener("click", function (event) {

            event.preventDefault();
            event.stopPropagation();

            closeMobileSidebar();

        });

    }


    if (sidebarOverlay) {

        sidebarOverlay.addEventListener("click", function () {

            closeMobileSidebar();

        });

    }


    /* =========================================================
       SIDEBAR LINKS
    ========================================================= */

    sidebarLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            sidebarLinks.forEach(function (item) {
                item.classList.remove("active");
            });

            this.classList.add("active");

            if (isMobile()) {
                closeMobileSidebar();
            }

        });

    });


    /* =========================================================
       ESC KEY
    ========================================================= */

    document.addEventListener("keydown", function (event) {

        if (event.key !== "Escape") {
            return;
        }

        if (sidebar && sidebar.classList.contains("mobile-open")) {
            closeMobileSidebar();
        }

        closeLogoutModal();

    });


    /* =========================================================
       RESIZE
    ========================================================= */

    let resizeTimer = null;

    window.addEventListener("resize", function () {

        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {

            if (!isMobile()) {
                closeMobileSidebar();
            } else if (!sidebar || !sidebar.classList.contains("mobile-open")) {
                restoreMenuButton();
            }

        }, 100);

    });


    /* =========================================================
       LOGOUT MODAL
    ========================================================= */

    function openLogoutModal() {

        if (!logoutModal) {
            return;
        }

        closeMobileSidebar();

        logoutModal.classList.add("active");
        document.body.style.overflow = "hidden";

    }


    function closeLogoutModal() {

        if (!logoutModal) {
            return;
        }

        logoutModal.classList.remove("active");

        if (!sidebar || !sidebar.classList.contains("mobile-open")) {
            document.body.style.overflow = "";
        }

    }


    if (logoutBtn) {
        logoutBtn.addEventListener("click", openLogoutModal);
    }

    if (logoutAllBtn) {
        logoutAllBtn.addEventListener("click", openLogoutModal);
    }

    if (cancelLogout) {
        cancelLogout.addEventListener("click", closeLogoutModal);
    }

    if (confirmLogout) {

        confirmLogout.addEventListener("click", function () {
            window.location.href = "logout.php";
        });

    }

    if (logoutModal) {

        logoutModal.addEventListener("click", function (event) {

            if (event.target === logoutModal) {
                closeLogoutModal();
            }

        });

    }


    /* =========================================================
       SUCCESS / ERROR MESSAGE
    ========================================================= */

    function hideMessage() {

        if (!messageBox) {
            return;
        }

        messageBox.style.transition = "opacity 0.3s ease, transform 0.3s ease";
        messageBox.style.opacity = "0";
        messageBox.style.transform = "translateY(-8px)";

        setTimeout(function () {
            messageBox.style.display = "none";
        }, 300);

    }

    if (messageClose) {
        messageClose.addEventListener("click", hideMessage);
    }

    if (messageBox) {
        setTimeout(hideMessage, 5000);
    }


    /* =========================================================
       DARK MODE
    ========================================================= */

    if (darkMode) {

        const savedDarkMode = localStorage.getItem("adminDarkMode");

        if (savedDarkMode === "true") {
            darkMode.checked = true;
            document.body.classList.add("dark-mode");
        }

        darkMode.addEventListener("change", function () {

            if (this.checked) {
                document.body.classList.add("dark-mode");
                localStorage.setItem("adminDarkMode", "true");
            } else {
                document.body.classList.remove("dark-mode");
                localStorage.setItem("adminDarkMode", "false");
            }

        });

    }


    /* =========================================================
       SETTINGS TOGGLE LOGGING
    ========================================================= */

    document.querySelectorAll(".switch input").forEach(function (toggle) {

        toggle.addEventListener("change", function () {
            console.log(this.id + ": " + (this.checked ? "ON" : "OFF"));
        });

    });


    /* =========================================================
       PREVENT SIDEBAR CLICKS FROM CLOSING IT
       UNLESS AN ACTION IS CLICKED
    ========================================================= */

    if (sidebar) {

        sidebar.addEventListener("click", function (event) {
            event.stopPropagation();
        });

    }


    /* =========================================================
       INITIAL STATE
    ========================================================= */

    ensureCloseButtonVisible();

    if (isMobile()) {
        closeMobileSidebar();
    }

});