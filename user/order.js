/* =========================================================
   KATES GOODIES
   ORDER PAGE JAVASCRIPT
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

    const filterButtons =
        document.querySelectorAll(".filter-button");

    const orderCards =
        document.querySelectorAll(".order-card");

    const noOrders =
        document.getElementById("noOrders");

    const orderModal =
        document.getElementById("orderModal");

    const viewOrderButtons =
        document.querySelectorAll(".view-order-button");


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

        const modalOpen =
            orderModal &&
            orderModal.classList.contains("is-open");

        document.body.style.overflow =
            navigationOpen || modalOpen
                ? "hidden"
                : "";

    }


    /* =========================================================
       ACCOUNT DROPDOWN
    ========================================================= */

    function openAccount() {

        if (
            !accountDropdown ||
            !accountButton
        ) {

            return;

        }


        accountDropdown.classList.add(
            "show"
        );


        accountButton.setAttribute(
            "aria-expanded",
            "true"
        );

    }


    function closeAccount() {

        if (
            !accountDropdown ||
            !accountButton
        ) {

            return;

        }


        accountDropdown.classList.remove(
            "show"
        );


        accountButton.setAttribute(
            "aria-expanded",
            "false"
        );

    }


    function toggleAccount(event) {

        event.preventDefault();

        event.stopPropagation();


        if (
            accountDropdown.classList.contains(
                "show"
            )
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


        mainNav.classList.add(
            "is-open"
        );


        if (navOverlay) {

            navOverlay.classList.add(
                "show"
            );

        }


        if (navToggle) {

            navToggle.classList.add(
                "active"
            );


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


        mainNav.classList.remove(
            "is-open"
        );


        if (navOverlay) {

            navOverlay.classList.remove(
                "show"
            );

        }


        if (navToggle) {

            navToggle.classList.remove(
                "active"
            );


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
            mainNav.classList.contains(
                "is-open"
            )
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
       ESCAPE KEY
    ========================================================= */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key !== "Escape"
            ) {

                return;

            }


            if (
                orderModal &&
                orderModal.classList.contains(
                    "is-open"
                )
            ) {

                closeOrderModal();

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
                    JSON.parse(
                        savedCart
                    );


                if (
                    Array.isArray(
                        parsedCart
                    )
                ) {

                    cart =
                        parsedCart;

                }

            }

        } catch (error) {

            cart = [];

        }


        let totalItems = 0;


        cart.forEach(
            function (item) {

                const quantity =
                    Number(
                        item.quantity
                    );


                if (
                    Number.isFinite(
                        quantity
                    ) &&
                    quantity > 0
                ) {

                    totalItems +=
                        Math.floor(
                            quantity
                        );

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
       ORDER FILTERS
    ========================================================= */

    function filterOrders(
        selectedFilter
    ) {

        let visibleOrders = 0;


        orderCards.forEach(
            function (card) {

                const status =
                    (
                        card.getAttribute(
                            "data-status"
                        ) || ""
                    )
                    .toLowerCase();


                const matches =
                    selectedFilter === "all" ||
                    status === selectedFilter;


                if (matches) {

                    card.style.display =
                        "";

                    visibleOrders++;

                } else {

                    card.style.display =
                        "none";

                }

            }
        );


        if (noOrders) {

            if (visibleOrders === 0) {

                noOrders.classList.add(
                    "show"
                );

            } else {

                noOrders.classList.remove(
                    "show"
                );

            }

        }

    }


    filterButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    filterButtons.forEach(
                        function (item) {

                            item.classList.remove(
                                "active"
                            );

                        }
                    );


                    button.classList.add(
                        "active"
                    );


                    const filter =
                        (
                            button.getAttribute(
                                "data-filter"
                            ) || "all"
                        )
                        .toLowerCase();


                    filterOrders(
                        filter
                    );

                }
            );

        }
    );


    /* =========================================================
       ORDER MODAL
    ========================================================= */

    function openOrderModal(
        orderNumber
    ) {

        if (!orderModal) {

            return;

        }


        closeAccount();

        closeNavigation();


        const modalOrderNumber =
            document.getElementById(
                "modalOrderNumber"
            );


        const modalOrderDate =
            document.getElementById(
                "modalOrderDate"
            );


        const modalOrderTotal =
            document.getElementById(
                "modalOrderTotal"
            );


        if (
            orderNumber === "KG-0987"
        ) {

            if (modalOrderNumber) {

                modalOrderNumber.textContent =
                    "Order #KG-0987";

            }


            if (modalOrderDate) {

                modalOrderDate.textContent =
                    "September 8, 2026";

            }


            if (modalOrderTotal) {

                modalOrderTotal.textContent =
                    "₱580";

            }

        } else {

            if (modalOrderNumber) {

                modalOrderNumber.textContent =
                    "Order #KG-1001";

            }


            if (modalOrderDate) {

                modalOrderDate.textContent =
                    "September 12, 2026";

            }


            if (modalOrderTotal) {

                modalOrderTotal.textContent =
                    "₱890";

            }

        }


        orderModal.classList.add(
            "is-open"
        );


        orderModal.setAttribute(
            "aria-hidden",
            "false"
        );


        updateBodyLock();

    }


    function closeOrderModal() {

        if (!orderModal) {

            return;

        }


        orderModal.classList.remove(
            "is-open"
        );


        orderModal.setAttribute(
            "aria-hidden",
            "true"
        );


        updateBodyLock();

    }


    viewOrderButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    const orderNumber =
                        button.getAttribute(
                            "data-order"
                        );


                    openOrderModal(
                        orderNumber
                    );

                }
            );

        }
    );


    /* =========================================================
       CLOSE ORDER MODAL
    ========================================================= */

    const modalClosers =
        document.querySelectorAll(
            "[data-close-order]"
        );


    modalClosers.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    closeOrderModal();

                }
            );

        }
    );


    /* =========================================================
       MODAL BACKDROP
    ========================================================= */

    if (orderModal) {

        orderModal.addEventListener(
            "click",
            function (event) {

                if (
                    event.target ===
                    orderModal
                ) {

                    closeOrderModal();

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

            clearTimeout(
                resizeTimer
            );


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

    filterOrders("all");

})();