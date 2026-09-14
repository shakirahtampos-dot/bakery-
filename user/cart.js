(function () {

    "use strict";


    /* =========================================================
       ACCOUNT + MOBILE NAVIGATION
    ========================================================= */

    const accountContainer =
        document.getElementById("accountContainer");

    const accountButton =
        document.getElementById("accountButton");

    const accountDropdown =
        document.getElementById("accountDropdown");

    const navbarActions =
        document.querySelector(".navbar-actions");

    const mainNav =
        document.getElementById("mainNav");

    const navToggle =
        document.getElementById("navToggle");

    const navClose =
        document.getElementById("navClose");

    const navOverlay =
        document.getElementById("navOverlay");


    /* =========================================================
       ACCOUNT DROPDOWN
    ========================================================= */

    if (
        accountButton &&
        accountDropdown
    ) {

        accountButton.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                event.stopPropagation();


                const opened =
                    accountDropdown.classList.toggle(
                        "show"
                    );


                accountButton.setAttribute(
                    "aria-expanded",
                    opened ? "true" : "false"
                );

            }
        );


        accountDropdown.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

            }
        );

    }


    /* =========================================================
       MOVE ACCOUNT INTO MOBILE BURGER MENU
    ========================================================= */

    function updateMobileAccount() {

        if (
            !accountContainer ||
            !mainNav ||
            !navbarActions
        ) {

            return;

        }


        /*
           MOBILE:
           Move account inside the burger menu.
        */

        if (window.innerWidth <= 800) {

            if (
                !mainNav.contains(
                    accountContainer
                )
            ) {

                mainNav.appendChild(
                    accountContainer
                );

            }

        }


        /*
           DESKTOP:
           Move account back beside cart.
        */

        else {

            if (
                !navbarActions.contains(
                    accountContainer
                )
            ) {

                navbarActions.appendChild(
                    accountContainer
                );

            }

        }

    }


    updateMobileAccount();


    window.addEventListener(
        "resize",
        updateMobileAccount
    );


    /* =========================================================
       OPEN MOBILE NAVIGATION
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

            navToggle.setAttribute(
                "aria-expanded",
                "true"
            );

            navToggle.setAttribute(
                "aria-label",
                "Close navigation"
            );

        }


        document.body.classList.add(
            "nav-open"
        );

    }


    /* =========================================================
       CLOSE MOBILE NAVIGATION
    ========================================================= */

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

            navToggle.setAttribute(
                "aria-expanded",
                "false"
            );

            navToggle.setAttribute(
                "aria-label",
                "Open navigation"
            );

        }


        /*
           Close account dropdown.
        */

        if (accountDropdown) {

            accountDropdown.classList.remove(
                "show"
            );

        }


        if (accountButton) {

            accountButton.setAttribute(
                "aria-expanded",
                "false"
            );

        }


        document.body.classList.remove(
            "nav-open"
        );

    }


    /* =========================================================
       BURGER BUTTON
    ========================================================= */

    if (navToggle) {

        navToggle.addEventListener(
            "click",
            function () {

                if (
                    mainNav &&
                    mainNav.classList.contains(
                        "is-open"
                    )
                ) {

                    closeNavigation();

                }

                else {

                    openNavigation();

                }

            }
        );

    }


    /* =========================================================
       CLOSE BUTTON
    ========================================================= */

    if (navClose) {

        navClose.addEventListener(
            "click",
            closeNavigation
        );

    }


    /* =========================================================
       OVERLAY
    ========================================================= */

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
            .forEach(
                function (link) {

                    link.addEventListener(
                        "click",
                        function () {

                            closeNavigation();

                        }
                    );

                }
            );

    }


    /* =========================================================
       CLOSE ACCOUNT WHEN CLICKING OUTSIDE
    ========================================================= */

    document.addEventListener(
        "click",
        function (event) {

            if (
                !accountDropdown ||
                !accountButton ||
                !accountContainer
            ) {

                return;

            }


            /*
               Only apply desktop account
               outside-click behavior.
            */

            if (window.innerWidth > 800) {

                if (
                    !accountContainer.contains(
                        event.target
                    )
                ) {

                    accountDropdown.classList.remove(
                        "show"
                    );

                    accountButton.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                }

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
                event.key === "Escape"
            ) {

                closeNavigation();


                if (accountDropdown) {

                    accountDropdown.classList.remove(
                        "show"
                    );

                }

            }

        }
    );


    /* =========================================================
       CART ITEMS
    ========================================================= */

    const cartItems =
        document.querySelectorAll(
            ".cart-item"
        );

    const subtotalElement =
        document.getElementById(
            "subtotal"
        );

    const deliveryElement =
        document.getElementById(
            "deliveryFee"
        );

    const totalElement =
        document.getElementById(
            "total"
        );

    const emptyCart =
        document.getElementById(
            "emptyCart"
        );


    /* =========================================================
       FORMAT PESO
    ========================================================= */

    function formatPeso(amount) {

        return "₱" +
            Number(amount).toLocaleString(
                "en-PH",
                {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }
            );

    }


    /* =========================================================
       UPDATE CART
    ========================================================= */

    function updateCart() {

        let subtotal = 0;

        let activeItems = 0;


        cartItems.forEach(
            function (item) {

                if (
                    item.classList.contains(
                        "removed"
                    )
                ) {

                    return;

                }


                const price =
                    Number(
                        item.dataset.price
                    ) || 0;


                const quantityElement =
                    item.querySelector(
                        ".quantity"
                    );


                const quantity =
                    Number(
                        quantityElement
                            ? quantityElement.textContent
                            : 1
                    ) || 1;


                subtotal +=
                    price * quantity;


                activeItems++;

            }
        );


        /*
           Delivery is ₱50 when cart
           contains at least one item.
        */

        const delivery =
            activeItems > 0
                ? 50
                : 0;


        const total =
            subtotal + delivery;


        if (subtotalElement) {

            subtotalElement.textContent =
                formatPeso(subtotal);

        }


        if (deliveryElement) {

            deliveryElement.textContent =
                formatPeso(delivery);

        }


        if (totalElement) {

            totalElement.textContent =
                formatPeso(total);

        }


        /*
           Show empty cart.
        */

        if (emptyCart) {

            emptyCart.style.display =
                activeItems === 0
                    ? "block"
                    : "none";

        }


        /*
           Hide summary if cart is empty.
        */

        const summaryCard =
            document.querySelector(
                ".summary-card"
            );


        if (summaryCard) {

            summaryCard.style.display =
                activeItems === 0
                    ? "none"
                    : "block";

        }

    }


    /* =========================================================
       QUANTITY BUTTONS
    ========================================================= */

    cartItems.forEach(
        function (item) {

            const increaseButton =
                item.querySelector(
                    ".increase"
                );


            const decreaseButton =
                item.querySelector(
                    ".decrease"
                );


            const quantityElement =
                item.querySelector(
                    ".quantity"
                );


            if (
                increaseButton &&
                quantityElement
            ) {

                increaseButton.addEventListener(
                    "click",
                    function () {

                        let quantity =
                            Number(
                                quantityElement.textContent
                            ) || 1;


                        quantity++;


                        if (quantity > 100) {

                            quantity = 100;

                        }


                        quantityElement.textContent =
                            quantity;


                        updateCart();

                    }
                );

            }


            if (
                decreaseButton &&
                quantityElement
            ) {

                decreaseButton.addEventListener(
                    "click",
                    function () {

                        let quantity =
                            Number(
                                quantityElement.textContent
                            ) || 1;


                        quantity--;


                        if (quantity < 1) {

                            quantity = 1;

                        }


                        quantityElement.textContent =
                            quantity;


                        updateCart();

                    }
                );

            }

        }
    );


    /* =========================================================
       REMOVE MODAL
    ========================================================= */

    const removeModal =
        document.getElementById(
            "removeModal"
        );

    const removeModalClose =
        document.getElementById(
            "removeModalClose"
        );

    const removeModalBackdrop =
        document.getElementById(
            "removeModalBackdrop"
        );

    const cancelRemove =
        document.getElementById(
            "cancelRemove"
        );

    const confirmRemove =
        document.getElementById(
            "confirmRemove"
        );


    let itemToRemove = null;


    /* =========================================================
       OPEN REMOVE MODAL
    ========================================================= */

    function openRemoveModal(item) {

        itemToRemove = item;


        if (!removeModal) {

            return;

        }


        removeModal.classList.add(
            "is-open"
        );


        removeModal.setAttribute(
            "aria-hidden",
            "false"
        );


        document.body.style.overflow =
            "hidden";

    }


    /* =========================================================
       CLOSE REMOVE MODAL
    ========================================================= */

    function closeRemoveModal() {

        if (!removeModal) {

            return;

        }


        removeModal.classList.remove(
            "is-open"
        );


        removeModal.setAttribute(
            "aria-hidden",
            "true"
        );


        document.body.style.overflow =
            "";

        itemToRemove = null;

    }


    /* =========================================================
       REMOVE BUTTONS
    ========================================================= */

    cartItems.forEach(
        function (item) {

            const removeButton =
                item.querySelector(
                    ".remove-btn"
                );


            if (removeButton) {

                removeButton.addEventListener(
                    "click",
                    function () {

                        openRemoveModal(
                            item
                        );

                    }
                );

            }

        }
    );


    /* =========================================================
       CONFIRM REMOVE
    ========================================================= */

    if (confirmRemove) {

        confirmRemove.addEventListener(
            "click",
            function () {

                if (!itemToRemove) {

                    return;

                }


                /*
                   Store the item locally before
                   closing the modal.
                */

                const item =
                    itemToRemove;


                item.classList.add(
                    "removed"
                );


                closeRemoveModal();


                setTimeout(
                    function () {

                        item.remove();

                        updateCart();

                    },
                    300
                );

            }
        );

    }


    /* =========================================================
       CANCEL REMOVE
    ========================================================= */

    if (cancelRemove) {

        cancelRemove.addEventListener(
            "click",
            closeRemoveModal
        );

    }


    if (removeModalClose) {

        removeModalClose.addEventListener(
            "click",
            closeRemoveModal
        );

    }


    if (removeModalBackdrop) {

        removeModalBackdrop.addEventListener(
            "click",
            closeRemoveModal
        );

    }


    /* =========================================================
       CHECKOUT
    ========================================================= */

    const checkoutButton =
        document.getElementById(
            "checkoutButton"
        );


    if (checkoutButton) {

        checkoutButton.addEventListener(
            "click",
            function () {

                const activeItems =
                    document.querySelectorAll(
                        ".cart-item:not(.removed)"
                    );


                if (
                    activeItems.length === 0
                ) {

                    alert(
                        "Your cart is empty."
                    );

                    return;

                }


                window.location.href =
                    "checkout.php";

            }
        );

    }


    /* =========================================================
       CART COUNT
    ========================================================= */

    const cartCount =
        document.getElementById(
            "cartCount"
        );


    function updateCartCount() {

        if (!cartCount) {

            return;

        }


        let cart = [];


        try {

            cart =
                JSON.parse(
                    localStorage.getItem(
                        "katesGoodiesCart"
                    )
                ) || [];

        }

        catch (error) {

            cart = [];

        }


        let totalItems = 0;


        cart.forEach(
            function (item) {

                totalItems +=
                    Number(
                        item.quantity
                    ) || 1;

            }
        );


        /*
           If localStorage cart is empty,
           use the displayed cart items.
        */

        if (
            totalItems === 0 &&
            cartItems.length > 0
        ) {

            cartItems.forEach(
                function (item) {

                    if (
                        !item.classList.contains(
                            "removed"
                        )
                    ) {

                        const quantityElement =
                            item.querySelector(
                                ".quantity"
                            );


                        totalItems +=
                            Number(
                                quantityElement
                                    ? quantityElement.textContent
                                    : 1
                            ) || 1;

                    }

                }
            );

        }


        cartCount.textContent =
            totalItems;

    }


    updateCartCount();


    /* =========================================================
       UPDATE CART COUNT FROM OTHER TABS
    ========================================================= */

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


    /* =========================================================
       NAVBAR SHADOW ON SCROLL
    ========================================================= */

    const navbar =
        document.querySelector(
            ".navbar"
        );


    window.addEventListener(
        "scroll",
        function () {

            if (!navbar) {

                return;

            }


            if (window.scrollY > 20) {

                navbar.style.boxShadow =
                    "0 6px 25px rgba(0,0,0,.25)";

            }

            else {

                navbar.style.boxShadow =
                    "0 4px 20px rgba(0,0,0,.15)";

            }

        }
    );


    /* =========================================================
       INITIAL CART UPDATE
    ========================================================= */

    updateCart();


})();