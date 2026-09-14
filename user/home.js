/* =========================================================
   KATES GOODIES
   HOME PAGE JAVASCRIPT
========================================================= */

(function () {

    "use strict";


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const navbar =
        document.querySelector(".navbar");

    const mainNav =
        document.getElementById("mainNav");

    const navToggle =
        document.getElementById("navToggle");

    const navClose =
        document.getElementById("navClose");

    const navOverlay =
        document.getElementById("navOverlay");

    const accountContainer =
        document.querySelector(".account-container");

    const accountButton =
        document.getElementById("accountButton");

    const accountDropdown =
        document.getElementById("accountDropdown");

    const navbarActions =
        document.querySelector(".navbar-actions");

    const cartCount =
        document.getElementById("cartCount");

    const notificationCount =
        document.getElementById("notificationCount");

    const notificationButton =
        document.getElementById("notificationButton");

    const searchInput =
        document.getElementById("productSearch");

    const products =
        document.querySelectorAll(".featured-product");

    const noResults =
        document.getElementById("noResults");

    const messagesModal =
        document.getElementById("messagesModal");


    /* =========================================================
       NOTIFICATION STORAGE KEY
    ========================================================= */

    const NOTIFICATION_STORAGE_KEY =
        "katesGoodiesNotifications";


    /* =========================================================
       ORIGINAL ACCOUNT LOCATION
    ========================================================= */

    const originalAccountParent =
        accountContainer
            ? accountContainer.parentElement
            : null;

    const originalAccountNextSibling =
        accountContainer
            ? accountContainer.nextElementSibling
            : null;


    /* =========================================================
       BODY LOCK
    ========================================================= */

    function updateBodyLock() {

        const navigationOpen =
            mainNav &&
            mainNav.classList.contains("is-open");

        const messagesOpen =
            messagesModal &&
            messagesModal.classList.contains("is-open");

        document.body.style.overflow =
            navigationOpen || messagesOpen
                ? "hidden"
                : "";
    }


    /* =========================================================
       ACCOUNT
    ========================================================= */

    function openAccount() {

        if (!accountDropdown || !accountButton) {
            return;
        }

        accountDropdown.classList.add("show");

        accountButton.setAttribute(
            "aria-expanded",
            "true"
        );
    }


    function closeAccount() {

        if (!accountDropdown || !accountButton) {
            return;
        }

        accountDropdown.classList.remove("show");

        accountButton.setAttribute(
            "aria-expanded",
            "false"
        );
    }


    function toggleAccount(event) {

        event.preventDefault();

        event.stopPropagation();

        if (
            accountDropdown.classList.contains("show")
        ) {

            closeAccount();

        } else {

            openAccount();

        }
    }


    if (
        accountButton &&
        accountDropdown
    ) {

        accountButton.addEventListener(
            "click",
            toggleAccount
        );


        accountDropdown.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

            }
        );
    }


    /* =========================================================
       MOBILE ACCOUNT LOCATION
    ========================================================= */

    function updateAccountLocation() {

        if (
            !accountContainer ||
            !mainNav ||
            !navbarActions
        ) {
            return;
        }

        const isMobile =
            window.matchMedia(
                "(max-width: 800px)"
            ).matches;


        if (isMobile) {

            if (
                !mainNav.contains(
                    accountContainer
                )
            ) {

                mainNav.appendChild(
                    accountContainer
                );
            }

        } else {

            if (
                !navbarActions.contains(
                    accountContainer
                )
            ) {

                if (
                    originalAccountNextSibling &&
                    originalAccountNextSibling.parentElement ===
                    navbarActions
                ) {

                    navbarActions.insertBefore(
                        accountContainer,
                        originalAccountNextSibling
                    );

                } else {

                    navbarActions.appendChild(
                        accountContainer
                    );
                }
            }
        }
    }


    /* =========================================================
       MOBILE NAVIGATION
    ========================================================= */

    function openNavigation() {

        if (!mainNav) {
            return;
        }

        mainNav.classList.add("is-open");

        document.body.classList.add("nav-open");

        if (navOverlay) {

            navOverlay.classList.add("show");

        }

        if (navToggle) {

            navToggle.classList.add("active");

            navToggle.setAttribute(
                "aria-expanded",
                "true"
            );

            navToggle.setAttribute(
                "aria-label",
                "Close navigation"
            );
        }

        updateBodyLock();
    }


    function closeNavigation() {

        if (!mainNav) {
            return;
        }

        mainNav.classList.remove("is-open");

        document.body.classList.remove("nav-open");

        if (navOverlay) {

            navOverlay.classList.remove("show");

        }

        if (navToggle) {

            navToggle.classList.remove("active");

            navToggle.setAttribute(
                "aria-expanded",
                "false"
            );

            navToggle.setAttribute(
                "aria-label",
                "Open navigation"
            );
        }

        updateBodyLock();
    }


    function toggleNavigation() {

        if (!mainNav) {
            return;
        }

        if (
            mainNav.classList.contains("is-open")
        ) {

            closeNavigation();

        } else {

            openNavigation();

        }
    }


    if (navToggle) {

        navToggle.addEventListener(
            "click",
            toggleNavigation
        );

    }


    if (navClose) {

        navClose.addEventListener(
            "click",
            closeNavigation
        );

    }


    if (navOverlay) {

        navOverlay.addEventListener(
            "click",
            closeNavigation
        );

    }


    /* =========================================================
       NAVIGATION LINKS
    ========================================================= */

    if (mainNav) {

        mainNav
            .querySelectorAll("a")
            .forEach(function (link) {

                link.addEventListener(
                    "click",
                    function () {

                        if (
                            link.closest(
                                ".account-container"
                            )
                        ) {

                            return;
                        }

                        closeNavigation();

                    }
                );

            });
    }


    /* =========================================================
       CLICK OUTSIDE ACCOUNT
    ========================================================= */

    document.addEventListener(
        "click",
        function (event) {

            if (
                !accountContainer ||
                !accountDropdown ||
                !accountButton
            ) {
                return;
            }

            if (
                !accountContainer.contains(
                    event.target
                )
            ) {

                closeAccount();

            }
        }
    );


    /* =========================================================
       ESCAPE
    ========================================================= */

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key !== "Escape") {
                return;
            }


            if (
                messagesModal &&
                messagesModal.classList.contains(
                    "is-open"
                )
            ) {

                closeMessages();

                return;
            }


            if (
                mainNav &&
                mainNav.classList.contains(
                    "is-open"
                )
            ) {

                closeNavigation();
            }


            closeAccount();

        }
    );


    /* =========================================================
       PRODUCT SEARCH
    ========================================================= */

    if (searchInput) {

        searchInput.addEventListener(
            "input",
            function () {

                const searchValue =
                    this.value
                        .toLocaleLowerCase()
                        .trim();


                let visibleProducts = 0;


                products.forEach(
                    function (product) {

                        const productName =
                            (
                                product.getAttribute(
                                    "data-name"
                                ) || ""
                            )
                            .toLocaleLowerCase();


                        const matches =
                            productName.includes(
                                searchValue
                            );


                        if (matches) {

                            product.style.display =
                                "";

                            visibleProducts++;

                        } else {

                            product.style.display =
                                "none";
                        }

                    }
                );


                if (noResults) {

                    noResults.style.display =
                        visibleProducts === 0
                            ? "block"
                            : "none";

                }

            }
        );
    }


    /* =========================================================
       CART COUNT
    ========================================================= */

    function updateCartCount() {

        if (!cartCount) {
            return;
        }


        let cart = [];


        try {

            const savedCart =
                localStorage.getItem(
                    "katesGoodiesCart"
                );


            if (savedCart) {

                const parsedCart =
                    JSON.parse(savedCart);


                if (Array.isArray(parsedCart)) {

                    cart = parsedCart;

                }
            }

        } catch (error) {

            cart = [];

        }


        let totalItems = 0;


        cart.forEach(
            function (item) {

                const quantity =
                    Number(item.quantity);


                if (
                    Number.isFinite(quantity) &&
                    quantity > 0
                ) {

                    totalItems +=
                        Math.floor(quantity);

                } else {

                    totalItems += 1;

                }

            }
        );


        cartCount.textContent =
            totalItems;


        if (totalItems > 0) {

            cartCount.style.display =
                "flex";

        } else {

            cartCount.style.display =
                "none";
        }

    }


    updateCartCount();


    window.addEventListener(
        "storage",
        function (event) {

            if (
                event.key ===
                "katesGoodiesCart"
            ) {

                updateCartCount();

            }

        }
    );


    window.addEventListener(
        "pageshow",
        updateCartCount
    );


    /* =========================================================
       NOTIFICATIONS
    ========================================================= */

    function getNotifications() {

        try {

            const saved =
                localStorage.getItem(
                    NOTIFICATION_STORAGE_KEY
                );


            if (!saved) {
                return [];
            }


            const parsed =
                JSON.parse(saved);


            if (Array.isArray(parsed)) {
                return parsed;
            }


        } catch (error) {

            console.error(
                "Unable to load notifications:",
                error
            );

        }


        return [];
    }


    function updateNotificationBadge() {

        if (!notificationCount) {
            return;
        }


        const notifications =
            getNotifications();


        const unreadCount =
            notifications.filter(
                function (notification) {

                    return notification.read !== true;

                }
            ).length;


        if (unreadCount > 0) {

            notificationCount.textContent =
                unreadCount > 99
                    ? "99+"
                    : unreadCount;

            notificationCount.classList.add(
                "show"
            );


            if (notificationButton) {

                notificationButton.classList.add(
                    "has-unread"
                );

            }

        } else {

            notificationCount.textContent =
                "0";

            notificationCount.classList.remove(
                "show"
            );


            if (notificationButton) {

                notificationButton.classList.remove(
                    "has-unread"
                );

            }

        }
    }


    /*
     * Create starter notifications only
     * when there are none yet.
     */

    function initializeNotifications() {

        const existing =
            localStorage.getItem(
                NOTIFICATION_STORAGE_KEY
            );


        if (existing !== null) {
            return;
        }


        const starterNotifications = [

            {
                id: Date.now() + 1,

                type: "welcome",

                title:
                    "Welcome to Kates Goodies!",

                message:
                    "Thank you for joining Kates Goodies. We are happy to have you with us.",

                date:
                    new Date().toISOString(),

                read: false
            },


            {
                id: Date.now() + 2,

                type: "promo",

                title:
                    "Freshly Baked Every Morning",

                message:
                    "Our delicious cookies, cakes, cupcakes and pastries are prepared fresh for you.",

                date:
                    new Date().toISOString(),

                read: false
            },


            {
                id: Date.now() + 3,

                type: "order",

                title:
                    "Pre-Order Reminder",

                message:
                    "Order before 10 AM and pick up your freshly baked treats by noon.",

                date:
                    new Date().toISOString(),

                read: false
            }

        ];


        localStorage.setItem(
            NOTIFICATION_STORAGE_KEY,
            JSON.stringify(
                starterNotifications
            )
        );

    }


    initializeNotifications();

    updateNotificationBadge();


    /*
     * Update badge when another tab
     * modifies notifications.
     */

    window.addEventListener(
        "storage",
        function (event) {

            if (
                event.key ===
                NOTIFICATION_STORAGE_KEY
            ) {

                updateNotificationBadge();

            }

        }
    );


    /*
     * Recheck when returning to page.
     */

    window.addEventListener(
        "pageshow",
        updateNotificationBadge
    );


    /* =========================================================
       MESSAGES MODAL
    ========================================================= */

    const messageOpeners =
        document.querySelectorAll(
            "[data-open-messages]"
        );


    const messageClosers =
        document.querySelectorAll(
            "[data-close-messages]"
        );


    function openMessages() {

        if (!messagesModal) {
            return;
        }


        closeAccount();

        closeNavigation();


        messagesModal.classList.add(
            "is-open"
        );


        messagesModal.setAttribute(
            "aria-hidden",
            "false"
        );


        updateBodyLock();

    }


    function closeMessages() {

        if (!messagesModal) {
            return;
        }


        messagesModal.classList.remove(
            "is-open"
        );


        messagesModal.setAttribute(
            "aria-hidden",
            "true"
        );


        updateBodyLock();

    }


    messageOpeners.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    openMessages();

                }
            );

        }
    );


    messageClosers.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    closeMessages();

                }
            );

        }
    );


    if (messagesModal) {

        messagesModal.addEventListener(
            "click",
            function (event) {

                if (
                    event.target ===
                    messagesModal
                ) {

                    closeMessages();

                }

            }
        );

    }


    /* =========================================================
       NAVBAR SHADOW
    ========================================================= */

    function updateNavbarShadow() {

        if (!navbar) {
            return;
        }


        if (window.scrollY > 20) {

            navbar.style.boxShadow =
                "0 7px 25px rgba(0,0,0,.25)";

        } else {

            navbar.style.boxShadow =
                "0 4px 20px rgba(0,0,0,.15)";

        }

    }


    window.addEventListener(
        "scroll",
        updateNavbarShadow,
        {
            passive: true
        }
    );


    updateNavbarShadow();


    /* =========================================================
       RESPONSIVE RESIZE
    ========================================================= */

    let resizeTimer = null;


    window.addEventListener(
        "resize",
        function () {

            clearTimeout(resizeTimer);


            resizeTimer =
                setTimeout(
                    function () {

                        updateAccountLocation();


                        if (
                            window.innerWidth > 800
                        ) {

                            closeNavigation();

                        }

                    },
                    100
                );

        }
    );


    /* =========================================================
       INITIALIZE
    ========================================================= */

    updateAccountLocation();

})();