
"use strict";

document.addEventListener("DOMContentLoaded", function () {

    /* =========================================================
       SIDEBAR ELEMENTS
    ========================================================= */

    const sidebar =
        document.getElementById("sidebar");

    const mobileMenuBtn =
        document.getElementById("mobileMenuBtn");

    const menuIcon =
        document.getElementById("menuIcon");

    const sidebarOverlay =
        document.getElementById("sidebarOverlay");

    const sidebarLinks =
        document.querySelectorAll(".sidebar-link");

    const logoutBtn =
        document.getElementById("logoutBtn");

    const logoutModal =
        document.getElementById("logoutModal");

    const confirmLogout =
        document.getElementById("confirmLogout");

    const cancelLogout =
        document.getElementById("cancelLogout");


    let sidebarOpen = false;


    /* =========================================================
       BODY SCROLL
    ========================================================= */

    function lockBodyScroll() {
        document.body.style.overflow = "hidden";
    }

    function unlockBodyScroll() {
        document.body.style.overflow = "";
    }


    /* =========================================================
       ACTIVE PAGE
    ========================================================= */

    function setActivePage() {

        const currentPage =
            window.location.pathname.split("/").pop();

        sidebarLinks.forEach(function (link) {

            const page =
                link.getAttribute("data-page");

            link.classList.toggle(
                "active",
                page === currentPage
            );

        });

    }

    setActivePage();


    /* =========================================================
       MENU ICON
    ========================================================= */

    function updateMenuIcon() {

        if (!menuIcon || !mobileMenuBtn) {
            return;
        }

        if (sidebarOpen) {

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


    /* =========================================================
       OPEN SIDEBAR
    ========================================================= */

    function openMobileSidebar() {

        if (!sidebar || !sidebarOverlay) {
            return;
        }

        sidebarOpen = true;

        sidebar.classList.add("mobile-open");

        sidebarOverlay.classList.add("active");

        lockBodyScroll();

        updateMenuIcon();

    }


    /* =========================================================
       CLOSE SIDEBAR
    ========================================================= */

    function closeMobileSidebar() {

        if (!sidebar || !sidebarOverlay) {
            return;
        }

        sidebarOpen = false;

        sidebar.classList.remove("mobile-open");

        sidebarOverlay.classList.remove("active");

        unlockBodyScroll();

        updateMenuIcon();

    }


    /* =========================================================
       TOGGLE SIDEBAR
    ========================================================= */

    function toggleMobileSidebar() {

        if (sidebarOpen) {

            closeMobileSidebar();

        } else {

            openMobileSidebar();

        }

    }


    if (mobileMenuBtn) {

        mobileMenuBtn.addEventListener(
            "click",
            toggleMobileSidebar
        );

    }


    if (sidebarOverlay) {

        sidebarOverlay.addEventListener(
            "click",
            closeMobileSidebar
        );

    }


    /* =========================================================
       NAVIGATION LINKS
    ========================================================= */

    sidebarLinks.forEach(function (link) {

        link.addEventListener(
            "click",
            function () {

                sidebarLinks.forEach(function (item) {

                    item.classList.remove("active");

                });

                this.classList.add("active");

                if (window.innerWidth <= 768) {

                    closeMobileSidebar();

                }

            }
        );

    });


    /* =========================================================
       ESCAPE KEY
    ========================================================= */

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Escape") {

                if (sidebarOpen) {
                    closeMobileSidebar();
                }

                closeLogoutModal();

                closeDeleteModal();

            }

        }
    );


    /* =========================================================
       RESIZE
    ========================================================= */

    window.addEventListener(
        "resize",
        function () {

            if (window.innerWidth > 768) {

                closeMobileSidebar();

            }

        }
    );


    /* =========================================================
       LOGOUT MODAL
    ========================================================= */

    function openLogoutModal() {

        if (!logoutModal) {
            return;
        }

        logoutModal.classList.add("active");

        lockBodyScroll();

    }


    function closeLogoutModal() {

        if (!logoutModal) {
            return;
        }

        logoutModal.classList.remove("active");

        if (!sidebarOpen) {
            unlockBodyScroll();
        }

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


    /* =========================================================
       PRODUCT ELEMENTS
    ========================================================= */

    const deleteModal =
        document.getElementById("deleteModal");

    const cancelDelete =
        document.getElementById("cancelDelete");

    const confirmDelete =
        document.getElementById("confirmDelete");

    const deleteMessage =
        document.getElementById("deleteMessage");

    const uploadArea =
        document.getElementById("uploadArea");

    const imageInput =
        document.getElementById("image");

    const previewImage =
        document.getElementById("previewImage");

    const imagePreview =
        document.getElementById("imagePreview");

    const removeImageBtn =
        document.getElementById("removeImage");

    const uploadContent =
        document.getElementById("uploadContent");

    const productForm =
        document.getElementById("productForm");

    const formTitle =
        document.getElementById("formTitle");

    const submitBtn =
        document.getElementById("submitBtn");

    const cancelBtn =
        document.getElementById("cancelBtn");

    const productsBody =
        document.getElementById("productsBody");

    const productCountSpan =
        document.getElementById("productCount");


    let deleteProductId = null;

    let currentEditId = null;

    let products = [];


    /* =========================================================
       IMAGE PREVIEW
    ========================================================= */

    function clearImagePreview() {

        if (imageInput) {
            imageInput.value = "";
        }

        if (previewImage) {
            previewImage.src = "";
        }

        if (imagePreview) {
            imagePreview.style.display = "none";
        }

        if (uploadContent) {
            uploadContent.style.display = "block";
        }

    }


    function handleImagePreview() {

        if (!imageInput) {
            return;
        }

        const file =
            imageInput.files[0];

        if (!file) {

            clearImagePreview();

            return;

        }

        const validTypes = [
            "image/jpeg",
            "image/png",
            "image/gif"
        ];


        if (!validTypes.includes(file.type)) {

            alert(
                "Please select a valid image (JPG, PNG, GIF)."
            );

            clearImagePreview();

            return;

        }


        if (file.size > 5 * 1024 * 1024) {

            alert("Image must not exceed 5MB.");

            clearImagePreview();

            return;

        }


        const reader =
            new FileReader();


        reader.onload = function () {

            previewImage.src =
                reader.result;

            imagePreview.style.display =
                "block";

            uploadContent.style.display =
                "none";

        };


        reader.readAsDataURL(file);

    }


    if (uploadArea && imageInput) {

        uploadArea.addEventListener(
            "click",
            function () {
                imageInput.click();
            }
        );


        uploadArea.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Enter" ||
                    event.key === " "
                ) {

                    event.preventDefault();

                    imageInput.click();

                }

            }
        );


        uploadArea.addEventListener(
            "dragover",
            function (event) {

                event.preventDefault();

                uploadArea.classList.add("dragover");

            }
        );


        uploadArea.addEventListener(
            "dragleave",
            function () {

                uploadArea.classList.remove("dragover");

            }
        );


        uploadArea.addEventListener(
            "drop",
            function (event) {

                event.preventDefault();

                uploadArea.classList.remove("dragover");

                if (event.dataTransfer.files.length) {

                    imageInput.files =
                        event.dataTransfer.files;

                    handleImagePreview();

                }

            }
        );


        imageInput.addEventListener(
            "change",
            handleImagePreview
        );

    }


    if (removeImageBtn) {

        removeImageBtn.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

                clearImagePreview();

            }
        );

    }


    /* =========================================================
       FORM SUBMIT
    ========================================================= */

    if (productForm) {

        productForm.addEventListener(
            "submit",
            function (event) {

                event.preventDefault();


                const title =
                    document.getElementById("title")
                        .value.trim();


                const price =
                    parseFloat(
                        document.getElementById("price")
                            .value
                    );


                const imageSrc =
                    previewImage.src ||
                    "https://via.placeholder.com/50";


                if (currentEditId !== null) {

                    const product =
                        products.find(
                            function (item) {
                                return item.id === currentEditId;
                            }
                        );


                    if (product) {

                        product.title = title;

                        product.price = price;

                        if (previewImage.src) {

                            product.image = imageSrc;

                        }

                    }

                } else {

                    const id = Date.now();

                    products.push({

                        id: id,

                        title: title,

                        price: price,

                        image: imageSrc

                    });

                }


                renderProducts();

                resetForm();

            }
        );

    }


    /* =========================================================
       RESET FORM
    ========================================================= */

    function resetForm() {

        if (productForm) {
            productForm.reset();
        }

        clearImagePreview();

        if (formTitle) {
            formTitle.textContent =
                "Add New Product";
        }

        if (submitBtn) {
            submitBtn.textContent =
                "Add Product";
        }

        currentEditId = null;

    }


    if (cancelBtn) {

        cancelBtn.addEventListener(
            "click",
            resetForm
        );

    }


    /* =========================================================
       PRODUCT COUNT
    ========================================================= */

    function updateProductCount() {

        if (productCountSpan) {

            productCountSpan.textContent =
                products.length;

        }

    }


    /* =========================================================
       RENDER PRODUCTS
    ========================================================= */

    function renderProducts() {

        if (!productsBody) {
            return;
        }

        productsBody.innerHTML = "";


        products.forEach(
            function (product) {

                const tr =
                    document.createElement("tr");


                tr.innerHTML = `
                    <td>
                        <div class="product-info">

                            <img
                                src="${product.image || "https://via.placeholder.com/50"}"
                                alt="${product.title}"
                            >

                            <div>

                                <strong>${product.title}</strong>

                                <small>ID: ${product.id}</small>

                            </div>

                        </div>
                    </td>

                    <td>₱${product.price.toFixed(2)}</td>

                    <td>

                        <div class="actions">

                            <button
                                class="action-btn edit"
                                data-id="${product.id}"
                                type="button"
                            >
                                Edit
                            </button>

                            <button
                                class="action-btn delete"
                                data-id="${product.id}"
                                type="button"
                            >
                                Delete
                            </button>

                        </div>

                    </td>
                `;


                productsBody.appendChild(tr);

            }
        );


        attachProductActions();

        updateProductCount();

    }


    /* =========================================================
       PRODUCT ACTIONS
    ========================================================= */

    function attachProductActions() {


        document.querySelectorAll(
            ".actions .edit"
        ).forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const id =
                            parseInt(
                                button.dataset.id,
                                10
                            );


                        const product =
                            products.find(
                                function (item) {
                                    return item.id === id;
                                }
                            );


                        if (product) {

                            populateForm(product);

                        }

                    }
                );

            }
        );


        document.querySelectorAll(
            ".actions .delete"
        ).forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        deleteProductId =
                            parseInt(
                                button.dataset.id,
                                10
                            );


                        if (deleteMessage) {

                            deleteMessage.textContent =
                                `Are you sure you want to delete product ID ${deleteProductId}?`;

                        }


                        if (deleteModal) {

                            deleteModal.classList.add("active");

                        }

                    }
                );

            }
        );

    }


    /* =========================================================
       EDIT PRODUCT
    ========================================================= */

    function populateForm(product) {

        if (formTitle) {

            formTitle.textContent =
                "Edit Product";

        }


        if (submitBtn) {

            submitBtn.textContent =
                "Update Product";

        }


        document.getElementById("title").value =
            product.title;


        document.getElementById("price").value =
            product.price;


        currentEditId = product.id;


        const formSection =
            document.getElementById("productFormSection");


        if (formSection) {

            formSection.scrollIntoView({

                behavior: "smooth",

                block: "start"

            });

        }

    }


    /* =========================================================
       DELETE MODAL
    ========================================================= */

    function closeDeleteModal() {

        if (deleteModal) {

            deleteModal.classList.remove("active");

        }

        deleteProductId = null;

    }


    if (confirmDelete) {

        confirmDelete.addEventListener(
            "click",
            function () {

                if (deleteProductId === null) {
                    return;
                }


                products =
                    products.filter(
                        function (product) {

                            return product.id !== deleteProductId;

                        }
                    );


                closeDeleteModal();

                renderProducts();

            }
        );

    }


    if (cancelDelete) {

        cancelDelete.addEventListener(
            "click",
            closeDeleteModal
        );

    }


    if (deleteModal) {

        deleteModal.addEventListener(
            "click",
            function (event) {

                if (event.target === deleteModal) {

                    closeDeleteModal();

                }

            }
        );

    }


    /* =========================================================
       INITIALIZE
    ========================================================= */

    updateMenuIcon();

    renderProducts();

});