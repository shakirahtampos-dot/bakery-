(function () {

    "use strict";


    /* =========================================================
       OPTION TILES
    ========================================================= */

    const tileGroups =
        document.querySelectorAll(".tile-group");


    tileGroups.forEach(function (group) {

        const tiles =
            group.querySelectorAll(".option-tile");


        function refreshSelected() {

            tiles.forEach(function (tile) {

                const input =
                    tile.querySelector("input");

                tile.classList.toggle(
                    "is-selected",
                    !!input && input.checked
                );

            });

        }


        tiles.forEach(function (tile) {

            const input =
                tile.querySelector("input");

            if (!input) return;

            input.addEventListener(
                "change",
                refreshSelected
            );

        });


        refreshSelected();

    });


    /* =========================================================
       ORDER RECEIPT MODAL
    ========================================================= */

    const confirmModal =
        document.getElementById("orderConfirmModal");

    const confirmClosers =
        document.querySelectorAll("[data-close-confirm]");


    function openConfirmModal() {

        if (!confirmModal) return;

        confirmModal.classList.add("is-open");

        confirmModal.setAttribute(
            "aria-hidden",
            "false"
        );

        document.body.style.overflow = "hidden";

    }


    function closeConfirmModal() {

        if (!confirmModal) return;

        confirmModal.classList.remove("is-open");

        confirmModal.setAttribute(
            "aria-hidden",
            "true"
        );

        document.body.style.overflow = "";

    }


    /*
        NOTE: the "Done" button inside the generated receipt
        also carries [data-close-confirm], but it is injected
        into the DOM later by generateReceipt(). We delegate
        the click handling on the document so it works for
        buttons that don't exist yet at page load.
    */

    document.addEventListener(
        "click",
        function (event) {

            const closer =
                event.target.closest("[data-close-confirm]");

            if (!closer) return;

            event.preventDefault();

            closeConfirmModal();

        }
    );


    if (confirmModal) {

        confirmModal.addEventListener(
            "click",
            function (event) {

                if (event.target === confirmModal) {

                    closeConfirmModal();

                }

            }
        );

    }


    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape" &&
                confirmModal &&
                confirmModal.classList.contains("is-open")
            ) {

                closeConfirmModal();

            }

        }
    );


    /* =========================================================
       GENERATE ORDER NUMBER
    ========================================================= */

    function generateOrderNumber() {

        const now = new Date();

        const year =
            now.getFullYear();

        const month =
            String(now.getMonth() + 1).padStart(2, "0");

        const day =
            String(now.getDate()).padStart(2, "0");

        const random =
            Math.floor(1000 + Math.random() * 9000);

        return "KG-" + year + month + day + "-" + random;

    }


    /* =========================================================
       GET SELECTED RADIO VALUE
    ========================================================= */

    function getSelectedValue(name) {

        const selected =
            document.querySelector(
                'input[name="' + name + '"]:checked'
            );

        return selected
            ? selected.value
            : "";

    }


    /* =========================================================
       HTML ESCAPE
       Prevents user-entered information from becoming HTML.
    ========================================================= */

    function escapeHTML(value) {

        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");

    }


    /* =========================================================
       GENERATE RECEIPT
       Builds the full order receipt and injects it into the
       popup modal (#generatedReceipt), pulling data straight
       from the checkout form and the order ticket sidebar.
    ========================================================= */

    function generateReceipt() {

        const fullName =
            document.getElementById("fullName")?.value.trim() || "Customer";

        const contactNumber =
            document.getElementById("contactNumber")?.value.trim() || "-";

        const address =
            document.getElementById("address")?.value.trim() || "-";

        const orderNotes =
            document.getElementById("orderNotes")?.value.trim() || "None";

        const deliveryOption =
            getSelectedValue("deliveryOption");

        const paymentMethod =
            getSelectedValue("paymentMethod");


        const subtotal =
            document.getElementById("subtotalValue")?.textContent.trim() || "₱0";

        const deliveryFee =
            document.getElementById("deliveryFeeValue")?.textContent.trim() || "₱0";

        const total =
            document.getElementById("totalValue")?.textContent.trim() || "₱0";


        const orderItems =
            document.getElementById("orderItems");


        let itemsHTML = "";


        if (orderItems) {

            const items =
                orderItems.querySelectorAll(".order-item");


            items.forEach(function (item) {

                const name =
                    item.querySelector(".order-item-name");

                const price =
                    item.querySelector(".order-item-price");

                if (!name || !price) return;


                const quantity =
                    name.querySelector(".order-item-qty");


                let itemName =
                    name.textContent.trim();


                if (quantity) {

                    itemName =
                        itemName
                            .replace(quantity.textContent.trim(), "")
                            .trim();

                }


                const qtyText =
                    quantity
                        ? quantity.textContent.trim()
                        : "";


                itemsHTML += `
                    <div class="generated-receipt-item">
                        <div>
                            <strong>${escapeHTML(itemName)}</strong>
                            <small>${escapeHTML(qtyText)}</small>
                        </div>

                        <span>
                            ${escapeHTML(price.textContent.trim())}
                        </span>
                    </div>
                `;

            });

        }


        const orderNumber =
            generateOrderNumber();


        const currentDate =
            new Date();


        const formattedDate =
            currentDate.toLocaleDateString(
                "en-PH",
                {
                    year: "numeric",
                    month: "long",
                    day: "numeric"
                }
            );


        const formattedTime =
            currentDate.toLocaleTimeString(
                "en-PH",
                {
                    hour: "2-digit",
                    minute: "2-digit"
                }
            );


        const receiptContainer =
            document.getElementById("generatedReceipt");


        if (!receiptContainer) return;


        receiptContainer.innerHTML = `

            <div class="generated-receipt">

                <div class="generated-receipt-header">

                    <div class="generated-receipt-logo">
                        Kate's Goodies
                    </div>

                    <div class="generated-receipt-title">
                        ORDER RECEIPT
                    </div>

                    <div class="generated-receipt-status">
                        ✓ ORDER CONFIRMED
                    </div>

                </div>


                <div class="generated-receipt-divider"></div>


                <div class="generated-receipt-meta">

                    <div>
                        <span>Order No.</span>
                        <strong>${orderNumber}</strong>
                    </div>

                    <div>
                        <span>Date</span>
                        <strong>${formattedDate}</strong>
                    </div>

                    <div>
                        <span>Time</span>
                        <strong>${formattedTime}</strong>
                    </div>

                </div>


                <div class="generated-receipt-divider"></div>


                <div class="generated-receipt-section">

                    <h4>Customer Information</h4>

                    <p>
                        <span>Name</span>
                        <strong>${escapeHTML(fullName)}</strong>
                    </p>

                    <p>
                        <span>Contact</span>
                        <strong>${escapeHTML(contactNumber)}</strong>
                    </p>

                    <p>
                        <span>Address</span>
                        <strong>${escapeHTML(address)}</strong>
                    </p>

                </div>


                <div class="generated-receipt-section">

                    <h4>Order Items</h4>

                    ${itemsHTML}

                </div>


                <div class="generated-receipt-divider"></div>


                <div class="generated-receipt-section">

                    <p>
                        <span>Delivery</span>
                        <strong>${escapeHTML(deliveryOption)}</strong>
                    </p>

                    <p>
                        <span>Payment</span>
                        <strong>${escapeHTML(paymentMethod)}</strong>
                    </p>

                    ${
                        orderNotes !== "None"
                        ? `
                        <p>
                            <span>Notes</span>
                            <strong>${escapeHTML(orderNotes)}</strong>
                        </p>
                        `
                        : ""
                    }

                </div>


                <div class="generated-receipt-divider"></div>


                <div class="generated-receipt-totals">

                    <p>
                        <span>Subtotal</span>
                        <strong>${escapeHTML(subtotal)}</strong>
                    </p>

                    <p>
                        <span>Delivery Fee</span>
                        <strong>${escapeHTML(deliveryFee)}</strong>
                    </p>

                    <p class="generated-grand-total">
                        <span>Total</span>
                        <strong>${escapeHTML(total)}</strong>
                    </p>

                </div>


                <div class="generated-receipt-footer">

                    <p>Thank you for ordering from Kate's Goodies!</p>

                    <small>
                        Fresh · Sweet · Homemade
                    </small>

                </div>


                <div class="generated-receipt-actions">

                    <button
                        type="button"
                        class="receipt-print-button"
                        id="printReceiptButton"
                    >
                        🖨 Print Receipt
                    </button>

                    <button
                        type="button"
                        class="receipt-done-button"
                        data-close-confirm
                    >
                        Done
                    </button>

                </div>

            </div>

        `;


        const printButton =
            document.getElementById("printReceiptButton");


        if (printButton) {

            printButton.addEventListener(
                "click",
                printReceipt
            );

        }

    }


    /* =========================================================
       PRINT RECEIPT
    ========================================================= */

    function printReceipt() {

        const receipt =
            document.querySelector(".generated-receipt");


        if (!receipt) return;


        const printWindow =
            window.open(
                "",
                "_blank",
                "width=700,height=800"
            );


        if (!printWindow) {

            alert(
                "Please allow pop-ups to print your receipt."
            );

            return;

        }


        printWindow.document.write(`

            <!DOCTYPE html>

            <html>

            <head>

                <title>Kate's Goodies Receipt</title>

                <style>

                    * {
                        box-sizing: border-box;
                    }

                    body {
                        margin: 0;
                        padding: 30px;
                        background: #ffffff;
                        color: #2b140b;
                        font-family: Arial, sans-serif;
                    }

                    .generated-receipt {
                        max-width: 600px;
                        margin: auto;
                    }

                    .generated-receipt-header {
                        text-align: center;
                    }

                    .generated-receipt-logo {
                        font-family: Georgia, serif;
                        font-size: 30px;
                        font-weight: bold;
                    }

                    .generated-receipt-title {
                        margin-top: 8px;
                        font-size: 13px;
                        letter-spacing: 3px;
                    }

                    .generated-receipt-status {
                        margin-top: 10px;
                        font-size: 12px;
                        font-weight: bold;
                    }

                    .generated-receipt-divider {
                        border-top: 1px dashed #999;
                        margin: 20px 0;
                    }

                    .generated-receipt-meta {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 15px;
                        text-align: center;
                    }

                    .generated-receipt-meta span,
                    .generated-receipt-meta strong {
                        display: block;
                    }

                    .generated-receipt-meta span {
                        font-size: 11px;
                        color: #777;
                    }

                    .generated-receipt-meta strong {
                        margin-top: 4px;
                        font-size: 12px;
                    }

                    .generated-receipt-section {
                        margin-bottom: 20px;
                    }

                    .generated-receipt-section h4 {
                        margin: 0 0 12px;
                        font-size: 13px;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                    }

                    .generated-receipt-section p,
                    .generated-receipt-item,
                    .generated-receipt-totals p {
                        display: flex;
                        justify-content: space-between;
                        gap: 15px;
                        margin: 8px 0;
                        font-size: 12px;
                    }

                    .generated-receipt-section p span,
                    .generated-receipt-totals p span {
                        color: #777;
                    }

                    .generated-receipt-item small {
                        display: block;
                        margin-top: 3px;
                        color: #777;
                    }

                    .generated-grand-total {
                        font-size: 17px !important;
                        font-weight: bold;
                        margin-top: 15px !important;
                    }

                    .generated-receipt-footer {
                        text-align: center;
                        margin-top: 30px;
                    }

                    .generated-receipt-footer p {
                        font-weight: bold;
                    }

                    .generated-receipt-footer small {
                        color: #777;
                    }

                    .generated-receipt-actions {
                        display: none;
                    }

                </style>

            </head>

            <body>

                ${receipt.outerHTML}

            </body>

            </html>

        `);


        printWindow.document.close();

        printWindow.focus();


        setTimeout(function () {

            printWindow.print();

        }, 300);

    }


    /* =========================================================
       CHECKOUT FORM
    ========================================================= */

    const checkoutForm =
        document.getElementById("checkoutForm");


    if (checkoutForm) {


        checkoutForm
            .querySelectorAll(
                ".form-group input, .form-group textarea"
            )
            .forEach(function (field) {

                field.addEventListener(
                    "blur",
                    function () {

                        field.classList.add(
                            "is-touched"
                        );

                    }
                );

            });


        checkoutForm.addEventListener(
            "submit",
            function (event) {

                event.preventDefault();


                if (!checkoutForm.checkValidity()) {

                    checkoutForm.reportValidity();

                    return;

                }


                /*
                    Generate the receipt BEFORE resetting
                    the form, otherwise customer information
                    would be lost. This also pops the modal
                    open right after "Place Order" is clicked.
                */

                generateReceipt();


                openConfirmModal();


                /*
                    Reset the form AFTER receipt information
                    has already been generated.
                */

                checkoutForm.reset();


                tileGroups.forEach(function (group) {

                    group
                        .querySelectorAll(".option-tile")
                        .forEach(function (tile) {

                            const input =
                                tile.querySelector("input");

                            tile.classList.toggle(
                                "is-selected",
                                !!input && input.checked
                            );

                        });

                });


                checkoutForm
                    .querySelectorAll(".is-touched")
                    .forEach(function (field) {

                        field.classList.remove(
                            "is-touched"
                        );

                    });

            }
        );

    }


})();