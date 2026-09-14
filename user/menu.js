/* =========================================================
   KATE'S GOODIES
   MENU JAVASCRIPT
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

    const navbarActions =
        document.querySelector(".navbar-actions");

    const accountButton =
        document.getElementById("accountButton");

    const accountDropdown =
        document.getElementById("accountDropdown");


    const searchInput =
        document.getElementById("searchInput");

    const productCards =
        document.querySelectorAll(".product-card");

    const categoryButtons =
        document.querySelectorAll(".category-btn");

    const noResults =
        document.getElementById("noResults");


    const cartCount =
        document.getElementById("cartCount");


    const toast =
        document.getElementById("toast");



    /* =========================================================
       MOBILE ACCOUNT POSITION
    ========================================================= */

    function updateMobileAccount() {

        if (
            !accountContainer ||
            !mainNav ||
            !navbarActions
        ) {
            return;
        }


        if (window.innerWidth <= 800) {

            /*
                Move account inside
                mobile navigation.
            */

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

            /*
                Move account back into
                navbar actions.
            */

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
                    opened
                        ? "true"
                        : "false"
                );

            }
        );


        accountDropdown.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

            }
        );


        document.addEventListener(
            "click",
            function (event) {

                if (

                    !accountDropdown.contains(
                        event.target
                    )

                    &&

                    !accountButton.contains(
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
        );

    }



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

        }


        document.body.style.overflow =
            "hidden";

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

        }


        document.body.style.overflow =
            "";

    }



    /* =========================================================
       BURGER BUTTON
    ========================================================= */

    if (navToggle) {

        navToggle.addEventListener(
            "click",
            function () {

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
       CLOSE MENU AFTER NAVIGATION
    ========================================================= */

    if (mainNav) {

        mainNav
            .querySelectorAll("a")
            .forEach(
                function (link) {

                    link.addEventListener(
                        "click",
                        closeNavigation
                    );

                }
            );

    }



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


                if (
                    accountDropdown
                ) {

                    accountDropdown.classList.remove(
                        "show"
                    );

                }

            }

        }
    );



    /* =========================================================
       PRODUCT FILTER
    ========================================================= */

    let currentCategory = "all";


    function filterProducts() {

        const searchValue =
            searchInput
                ? searchInput.value
                    .toLowerCase()
                    .trim()
                : "";


        let visibleProducts = 0;


        productCards.forEach(
            function (product) {

                const productName =
                    (
                        product.getAttribute(
                            "data-name"
                        ) || ""
                    )
                    .toLowerCase();


                const productCategory =
                    (
                        product.getAttribute(
                            "data-category"
                        ) || ""
                    )
                    .toLowerCase();


                const matchesSearch =
                    productName.includes(
                        searchValue
                    );


                const matchesCategory =
                    currentCategory === "all"
                    ||
                    productCategory ===
                    currentCategory;


                if (
                    matchesSearch &&
                    matchesCategory
                ) {

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



    /* =========================================================
       SEARCH INPUT
    ========================================================= */

    if (searchInput) {

        searchInput.addEventListener(
            "input",
            filterProducts
        );

    }



    /* =========================================================
       CATEGORY BUTTONS
    ========================================================= */

    categoryButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    /*
                       Remove active
                       from all buttons.
                    */

                    categoryButtons.forEach(
                        function (btn) {

                            btn.classList.remove(
                                "active"
                            );

                        }
                    );


                    /*
                       Add active
                       to clicked button.
                    */

                    this.classList.add(
                        "active"
                    );


                    currentCategory =
                        this.getAttribute(
                            "data-category"
                        );


                    filterProducts();

                }
            );

        }
    );



    /* =========================================================
       URL CATEGORY
    ========================================================= */

    const urlParams =
        new URLSearchParams(
            window.location.search
        );


    const urlCategory =
        urlParams.get("category");


    if (urlCategory) {

        const validCategories = [
            "all",
            "cookies",
            "cakes",
            "cupcakes",
            "brownies",
            "pastries"
        ];


        if (
            validCategories.includes(
                urlCategory.toLowerCase()
            )
        ) {

            currentCategory =
                urlCategory.toLowerCase();


            categoryButtons.forEach(
                function (button) {

                    button.classList.remove(
                        "active"
                    );


                    if (
                        button.getAttribute(
                            "data-category"
                        ) === currentCategory
                    ) {

                        button.classList.add(
                            "active"
                        );

                    }

                }
            );


            filterProducts();

        }

    }



    /* =========================================================
       CART STORAGE
    ========================================================= */

    function getCart() {

        let cart = [];


        try {

            cart =
                JSON.parse(
                    localStorage.getItem(
                        "katesGoodiesCart"
                    )
                ) || [];

        } catch (error) {

            cart = [];

        }


        return Array.isArray(cart)
            ? cart
            : [];

    }



    /* =========================================================
       SAVE CART
    ========================================================= */

    function saveCart(cart) {

        try {

            localStorage.setItem(
                "katesGoodiesCart",
                JSON.stringify(cart)
            );

        } catch (error) {

            console.error(
                "Unable to save cart:",
                error
            );

        }

    }



    /* =========================================================
       UPDATE CART COUNT
    ========================================================= */

    function updateCartCount() {

        if (!cartCount) {
            return;
        }


        const cart =
            getCart();


        let totalItems = 0;


        cart.forEach(
            function (item) {

                totalItems +=
                    Number(
                        item.quantity
                    ) || 1;

            }
        );


        cartCount.textContent =
            totalItems;

    }


    updateCartCount();



    /* =========================================================
       TOAST
    ========================================================= */

    let toastTimer;


    function showToast(message) {

        if (!toast) {
            return;
        }


        toast.textContent =
            message;


        toast.classList.add(
            "show"
        );


        clearTimeout(
            toastTimer
        );


        toastTimer =
            setTimeout(
                function () {

                    toast.classList.remove(
                        "show"
                    );

                },
                2500
            );

    }



    /* =========================================================
       ADD TO CART
    ========================================================= */

    window.addToCart =
        function (
            productName,
            price
        ) {

            const cart =
                getCart();


            const existingItem =
                cart.find(
                    function (item) {

                        return (
                            item.name ===
                            productName
                        );

                    }
                );


            if (existingItem) {

                existingItem.quantity =
                    (
                        Number(
                            existingItem.quantity
                        ) || 1
                    ) + 1;

            } else {

                cart.push({

                    name:
                        productName,

                    price:
                        Number(price),

                    quantity:
                        1

                });

            }


            saveCart(cart);


            updateCartCount();


            showToast(
                productName +
                " added to cart!"
            );

        };



    /* =========================================================
       ORDER NOW
    ========================================================= */

    window.orderNow =
        function (
            productName,
            price
        ) {

            const encodedName =
                encodeURIComponent(
                    productName
                );


            const encodedPrice =
                encodeURIComponent(
                    price
                );


            window.location.href =
                "order.php?product=" +
                encodedName +
                "&price=" +
                encodedPrice;

        };



    /* =========================================================
       CART UPDATE FROM OTHER TABS
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
       NAVBAR SHADOW
    ========================================================= */

    window.addEventListener(
        "scroll",
        function () {

            if (!navbar) {
                return;
            }


            if (
                window.scrollY > 20
            ) {

                navbar.style.boxShadow =
                    "0 6px 25px rgba(0,0,0,.25)";

            } else {

                navbar.style.boxShadow =
                    "0 4px 20px rgba(0,0,0,.15)";

            }

        }
    );



    /* =========================================================
       INITIAL FILTER
    ========================================================= */

    filterProducts();


})();