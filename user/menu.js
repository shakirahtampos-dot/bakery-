/*
|--------------------------------------------------------------------------
| KATES GOODIES - MENU JAVASCRIPT
|--------------------------------------------------------------------------
*/

"use strict";

/*
|--------------------------------------------------------------------------
| GLOBAL STATE
|--------------------------------------------------------------------------
*/

let selectedCategory =
    typeof initialCategory !== "undefined"
        ? initialCategory
        : "all";

let products = [];

let searchText = "";

/*
|--------------------------------------------------------------------------
| ELEMENTS
|--------------------------------------------------------------------------
*/

const productsGrid =
    document.getElementById("productsGrid");

const searchInput =
    document.getElementById("searchInput");

const noResults =
    document.getElementById("noResults");

const toast =
    document.getElementById("toast");

const cartCount =
    document.getElementById("cartCount");

/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHTML(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/*
|--------------------------------------------------------------------------
| FORMAT PRICE
|--------------------------------------------------------------------------
*/

function formatPrice(price) {
    return Number(price || 0).toLocaleString(
        "en-PH",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}

/*
|--------------------------------------------------------------------------
| SHOW TOAST
|--------------------------------------------------------------------------
*/

function showToast(message) {
    if (!toast) {
        alert(message);
        return;
    }

    toast.textContent = message;

    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 2500);
}

/*
|--------------------------------------------------------------------------
| LOAD PRODUCTS FROM PRODUCT.PHP
|--------------------------------------------------------------------------
*/

async function loadProducts() {
    if (!productsGrid) {
        return;
    }

    productsGrid.innerHTML = `
        <div class="loading-products">
            Loading freshly baked goodies...
        </div>
    `;

    try {
        const params = new URLSearchParams();

        params.set("category", selectedCategory);

        if (searchText !== "") {
            params.set("search", searchText);
        }

        const response = await fetch(
            "product.php?" + params.toString(),
            {
                method: "GET",
                headers: {
                    "Accept": "application/json"
                },
                cache: "no-store"
            }
        );

        if (!response.ok) {
            throw new Error("Unable to load products.");
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(
                data.message || "Unable to load products."
            );
        }

        products = Array.isArray(data.products)
            ? data.products
            : [];

        renderProducts();

    } catch (error) {
        console.error(error);

        productsGrid.innerHTML = `
            <div class="loading-products">
                Unable to load products.
                Please try again.
            </div>
        `;

        if (noResults) {
            noResults.style.display = "none";
        }
    }
}

/*
|--------------------------------------------------------------------------
| RENDER PRODUCTS
|--------------------------------------------------------------------------
*/

function renderProducts() {
    if (!productsGrid) {
        return;
    }

    if (products.length === 0) {
        productsGrid.innerHTML = "";

        if (noResults) {
            noResults.style.display = "flex";
        }

        return;
    }

    if (noResults) {
        noResults.style.display = "none";
    }

    productsGrid.innerHTML = products.map(product => {

        const productId =
            Number(product.id);

        const title =
            escapeHTML(product.title);

        const description =
            escapeHTML(product.description);

        const category =
            escapeHTML(product.category);

        const image =
            escapeHTML(
                product.image_url || "Katelogo.png"
            );

        const price =
            formatPrice(product.price);

        return `
            <article
                class="product-card"
                data-product-id="${productId}"
                data-category="${category.toLowerCase()}"
                data-name="${title}"
            >

                <div class="product-image">

                    <img
                        src="${image}"
                        alt="${title}"
                        onerror="this.onerror=null;this.src='Katelogo.png';"
                    >

                    <span class="product-category">
                        ${category}
                    </span>

                </div>

                <div class="product-info">

                    <h3>
                        ${title}
                    </h3>

                    <p>
                        ${description}
                    </p>

                    <div class="product-bottom">

                        <span class="price">
                            ₱${price}
                        </span>

                        <div class="product-buttons">

                            <button
                                class="add-cart"
                                type="button"
                                data-product-id="${productId}"
                                aria-label="Add ${title} to cart"
                            >

                                <i class="fa-solid fa-cart-plus"></i>

                            </button>

                            <button
                                class="order-now"
                                type="button"
                                data-product-id="${productId}"
                            >
                                Order
                            </button>

                        </div>

                    </div>

                </div>

            </article>
        `;

    }).join("");
}

/*
|--------------------------------------------------------------------------
| ADD TO CART
|--------------------------------------------------------------------------
*/

async function addToCart(productId, quantity = 1) {

    if (!productId || Number(productId) <= 0) {
        showToast("Invalid product.");
        return;
    }

    try {

        const response = await fetch(
            "cart.php?action=add",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },

                body: JSON.stringify({
                    action: "add",
                    product_id: Number(productId),
                    quantity: Number(quantity)
                })
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || "Unable to add item."
            );
        }

        if (cartCount && data.count !== undefined) {
            cartCount.textContent = data.count;
        }

        showToast("Item added to your cart!");

    } catch (error) {
        console.error(error);

        showToast(
            error.message || "Unable to add item to cart."
        );
    }
}

/*
|--------------------------------------------------------------------------
| ORDER NOW
|--------------------------------------------------------------------------
*/

async function orderNow(productId) {

    await addToCart(productId, 1);

    /*
    | Redirect only after the add-to-cart request completes.
    */

    window.location.href = "cart.php";
}

/*
|--------------------------------------------------------------------------
| PRODUCT BUTTON EVENTS
|--------------------------------------------------------------------------
*/

if (productsGrid) {

    productsGrid.addEventListener("click", event => {

        const addButton =
            event.target.closest(".add-cart");

        const orderButton =
            event.target.closest(".order-now");

        if (addButton) {

            const productId =
                Number(addButton.dataset.productId);

            addToCart(productId);

            return;
        }

        if (orderButton) {

            const productId =
                Number(orderButton.dataset.productId);

            orderNow(productId);

        }

    });

}

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

if (searchInput) {

    searchInput.addEventListener("input", () => {

        searchText =
            searchInput.value.trim();

        loadProducts();

    });

}

/*
|--------------------------------------------------------------------------
| CATEGORY BUTTONS
|--------------------------------------------------------------------------
*/

document.querySelectorAll(".category-btn")
    .forEach(button => {

        button.addEventListener("click", () => {

            selectedCategory =
                button.dataset.category || "all";

            document.querySelectorAll(".category-btn")
                .forEach(categoryButton => {

                    categoryButton.classList.toggle(
                        "active",
                        categoryButton === button
                    );

                });

            loadProducts();

        });

    });

/*
|--------------------------------------------------------------------------
| UPDATE CART COUNT
|--------------------------------------------------------------------------
*/

async function updateCartCount() {

    try {

        const response = await fetch(
            "cart.php?action=get",
            {
                method: "GET",
                headers: {
                    "Accept": "application/json"
                },
                cache: "no-store"
            }
        );

        const data = await response.json();

        if (
            data.success &&
            cartCount &&
            data.count !== undefined
        ) {
            cartCount.textContent = data.count;
        }

    } catch (error) {
        console.error(
            "Unable to update cart count:",
            error
        );
    }

}

/*
|--------------------------------------------------------------------------
| ACCOUNT DROPDOWN
|--------------------------------------------------------------------------
*/

const accountButton =
    document.getElementById("accountButton");

const accountDropdown =
    document.getElementById("accountDropdown");

if (accountButton && accountDropdown) {

    accountButton.addEventListener("click", event => {

        event.stopPropagation();

        const isOpen =
            accountDropdown.classList.toggle("show");

        accountButton.setAttribute(
            "aria-expanded",
            String(isOpen)
        );

    });

    document.addEventListener("click", event => {

        if (
            !event.target.closest(".account-container")
        ) {

            accountDropdown.classList.remove("show");

            accountButton.setAttribute(
                "aria-expanded",
                "false"
            );

        }

    });

}

/*
|--------------------------------------------------------------------------
| MOBILE NAVIGATION
|--------------------------------------------------------------------------
*/

const navToggle =
    document.getElementById("navToggle");

const navClose =
    document.getElementById("navClose");

const mainNav =
    document.getElementById("mainNav");

const navOverlay =
    document.getElementById("navOverlay");

function openNavigation() {

    if (!mainNav) {
        return;
    }

    mainNav.classList.add("is-open");

    if (navOverlay) {
        navOverlay.classList.add("show");
    }

    if (navToggle) {
        navToggle.classList.add("active");

        navToggle.setAttribute(
            "aria-expanded",
            "true"
        );
    }

}

function closeNavigation() {

    if (!mainNav) {
        return;
    }

    mainNav.classList.remove("is-open");

    if (navOverlay) {
        navOverlay.classList.remove("show");
    }

    if (navToggle) {
        navToggle.classList.remove("active");

        navToggle.setAttribute(
            "aria-expanded",
            "false"
        );
    }

}

if (navToggle) {
    navToggle.addEventListener(
        "click",
        openNavigation
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

/*
|--------------------------------------------------------------------------
| INITIAL LOAD
|--------------------------------------------------------------------------
*/

loadProducts();

updateCartCount();