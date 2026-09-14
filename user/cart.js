/*
|--------------------------------------------------------------------------
| KATES GOODIES - CART JAVASCRIPT
|--------------------------------------------------------------------------
*/

"use strict";

const cartItemsContainer =
    document.getElementById("cartItems");

const emptyCart =
    document.getElementById("emptyCart");

const subtotalElement =
    document.getElementById("subtotal");

const deliveryElement =
    document.getElementById("deliveryFee");

const totalElement =
    document.getElementById("total");

const cartCountElement =
    document.getElementById("cartCount");

const checkoutButton =
    document.getElementById("checkoutButton");

const removeModal =
    document.getElementById("removeModal");

const confirmRemove =
    document.getElementById("confirmRemove");

const cancelRemove =
    document.getElementById("cancelRemove");

const removeModalClose =
    document.getElementById("removeModalClose");

const removeModalBackdrop =
    document.getElementById("removeModalBackdrop");

let cartItems = [];

let itemToRemove = null;

/*
|--------------------------------------------------------------------------
| FORMAT CURRENCY
|--------------------------------------------------------------------------
*/

function formatCurrency(amount) {

    return Number(amount || 0).toLocaleString(
        "en-PH",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );

}

/*
|--------------------------------------------------------------------------
| UPDATE CART COUNT
|--------------------------------------------------------------------------
*/

function updateCartCount(count) {

    if (cartCountElement) {
        cartCountElement.textContent =
            Number(count || 0);
    }

}

/*
|--------------------------------------------------------------------------
| UPDATE SUMMARY
|--------------------------------------------------------------------------
*/

function updateSummary(data) {

    if (subtotalElement) {
        subtotalElement.textContent =
            "₱" + formatCurrency(data.subtotal);
    }

    if (deliveryElement) {
        deliveryElement.textContent =
            "₱" + formatCurrency(data.delivery);
    }

    if (totalElement) {
        totalElement.textContent =
            "₱" + formatCurrency(data.total);
    }

    updateCartCount(data.count);

    if (checkoutButton) {
        checkoutButton.disabled =
            !data.items || data.items.length === 0;
    }

}

/*
|--------------------------------------------------------------------------
| LOAD CART
|--------------------------------------------------------------------------
*/

async function loadCart() {

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

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || "Unable to load cart."
            );
        }

        cartItems = Array.isArray(data.items)
            ? data.items
            : [];

        renderCartItems(cartItems);

        updateSummary({
            ...data,
            items: cartItems
        });

    } catch (error) {

        console.error(error);

        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = `
                <p class="cart-error">
                    Unable to load your cart.
                    Please refresh the page.
                </p>
            `;
        }

    }

}

/*
|--------------------------------------------------------------------------
| RENDER CART ITEMS
|--------------------------------------------------------------------------
*/

function renderCartItems(items) {

    if (!cartItemsContainer) {
        return;
    }

    cartItemsContainer.innerHTML = "";

    if (!items.length) {

        if (emptyCart) {
            emptyCart.style.display = "flex";
        }

        return;
    }

    if (emptyCart) {
        emptyCart.style.display = "none";
    }

    items.forEach(item => {

        const itemId =
            Number(item.cart_item_id);

        const title =
            String(item.title || "Goodies");

        const category =
            String(item.category || "Goodies");

        const image =
            item.image_url || "Katelogo.png";

        const price =
            Number(item.price || 0);

        const quantity =
            Number(item.quantity || 0);

        const itemTotal =
            price * quantity;

        const itemElement =
            document.createElement("div");

        itemElement.className = "cart-item";

        itemElement.dataset.cartItemId = itemId;

        itemElement.innerHTML = `

            <div class="cart-item-image">

                <img
                    src="${escapeHTML(image)}"
                    alt="${escapeHTML(title)}"
                    onerror="this.onerror=null;this.src='Katelogo.png';"
                >

            </div>

            <div class="cart-item-details">

                <span class="cart-item-category">
                    ${escapeHTML(category)}
                </span>

                <h3>
                    ${escapeHTML(title)}
                </h3>

                <p class="cart-item-price">
                    ₱${formatCurrency(price)}
                </p>

                <div class="quantity-control">

                    <button
                        type="button"
                        class="quantity-btn decrease-btn"
                        data-action="decrease"
                        aria-label="Decrease quantity"
                    >
                        −
                    </button>

                    <span class="quantity">
                        ${quantity}
                    </span>

                    <button
                        type="button"
                        class="quantity-btn increase-btn"
                        data-action="increase"
                        aria-label="Increase quantity"
                    >
                        +
                    </button>

                </div>

            </div>

            <div class="cart-item-right">

                <strong class="cart-item-total">
                    ₱${formatCurrency(itemTotal)}
                </strong>

                <button
                    type="button"
                    class="remove-item"
                    data-cart-item-id="${itemId}"
                >

                    <i class="fa-solid fa-trash"></i>
                    Remove

                </button>

            </div>

        `;

        cartItemsContainer.appendChild(itemElement);

    });

}

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
| UPDATE ITEM QUANTITY
|--------------------------------------------------------------------------
*/

async function updateQuantity(cartItemId, quantity) {

    try {

        const response = await fetch(
            "cart.php?action=update",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },

                body: JSON.stringify({
                    action: "update",
                    cart_item_id: cartItemId,
                    quantity: quantity
                })
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || "Unable to update cart."
            );
        }

        await loadCart();

    } catch (error) {

        console.error(error);

        alert(
            error.message || "Unable to update cart."
        );

    }

}

/*
|--------------------------------------------------------------------------
| REMOVE MODAL
|--------------------------------------------------------------------------
*/

function openRemoveModal(cartItemId) {

    itemToRemove = Number(cartItemId);

    if (!removeModal) {
        return;
    }

    removeModal.classList.add("show");

    removeModal.setAttribute(
        "aria-hidden",
        "false"
    );

}

function closeRemoveModal() {

    itemToRemove = null;

    if (!removeModal) {
        return;
    }

    removeModal.classList.remove("show");

    removeModal.setAttribute(
        "aria-hidden",
        "true"
    );

}

async function removeItem(cartItemId) {

    try {

        const response = await fetch(
            "cart.php?action=remove",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },

                body: JSON.stringify({
                    action: "remove",
                    cart_item_id: cartItemId
                })
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || "Unable to remove item."
            );
        }

        closeRemoveModal();

        await loadCart();

    } catch (error) {

        console.error(error);

        alert(
            error.message || "Unable to remove item."
        );

    }

}

/*
|--------------------------------------------------------------------------
| CART ITEM BUTTON EVENTS
|--------------------------------------------------------------------------
*/

if (cartItemsContainer) {

    cartItemsContainer.addEventListener(
        "click",
        event => {

            const itemElement =
                event.target.closest(".cart-item");

            if (!itemElement) {
                return;
            }

            const cartItemId =
                Number(itemElement.dataset.cartItemId);

            const button =
                event.target.closest("button");

            if (!button) {
                return;
            }

            const item =
                cartItems.find(
                    currentItem =>
                        Number(currentItem.cart_item_id) === cartItemId
                );

            if (!item) {
                return;
            }

            const quantity =
                Number(item.quantity);

            if (
                button.classList.contains("increase-btn")
            ) {

                updateQuantity(
                    cartItemId,
                    quantity + 1
                );

            }

            if (
                button.classList.contains("decrease-btn")
            ) {

                updateQuantity(
                    cartItemId,
                    quantity - 1
                );

            }

            if (
                button.classList.contains("remove-item")
            ) {

                openRemoveModal(cartItemId);

            }

        }
    );

}

/*
|--------------------------------------------------------------------------
| REMOVE MODAL EVENTS
|--------------------------------------------------------------------------
*/

if (confirmRemove) {

    confirmRemove.addEventListener(
        "click",
        () => {

            if (itemToRemove !== null) {
                removeItem(itemToRemove);
            }

        }
    );

}

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

/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

if (checkoutButton) {

    checkoutButton.addEventListener(
        "click",
        () => {

            if (!cartItems.length) {
                return;
            }

            window.location.href = "checkout.php";

        }
    );

}

/*
|--------------------------------------------------------------------------
| INITIALIZE
|--------------------------------------------------------------------------
*/

loadCart();