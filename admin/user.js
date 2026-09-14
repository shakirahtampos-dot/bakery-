document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =====================================================
           ELEMENTS
           ===================================================== */

        const mobileMenuBtn =
            document.getElementById(
                "mobileMenuBtn"
            );

        const sidebar =
            document.getElementById(
                "sidebar"
            );

        const sidebarOverlay =
            document.getElementById(
                "sidebarOverlay"
            );

        const sidebarLinks =
            document.querySelectorAll(
                ".sidebar-link"
            );

        const logoutBtn =
            document.getElementById(
                "logoutBtn"
            );

        const logoutModal =
            document.getElementById(
                "logoutModal"
            );

        const cancelLogout =
            document.getElementById(
                "cancelLogout"
            );

        const confirmLogout =
            document.getElementById(
                "confirmLogout"
            );

        const deleteModal =
            document.getElementById(
                "deleteModal"
            );

        const cancelDelete =
            document.getElementById(
                "cancelDelete"
            );

        const confirmDelete =
            document.getElementById(
                "confirmDelete"
            );

        const deleteMessage =
            document.getElementById(
                "deleteMessage"
            );

        const passwordToggle =
            document.getElementById(
                "passwordToggle"
            );

        const passwordInput =
            document.getElementById(
                "password"
            );


        /* =====================================================
           MOBILE SIDEBAR
           ===================================================== */

        function openSidebar() {

            if (
                !sidebar ||
                !sidebarOverlay ||
                !mobileMenuBtn
            ) {
                return;
            }


            sidebar.classList.add(
                "mobile-open"
            );

            sidebarOverlay.classList.add(
                "active"
            );

            mobileMenuBtn.classList.add(
                "is-open"
            );


            mobileMenuBtn.setAttribute(
                "aria-expanded",
                "true"
            );

            mobileMenuBtn.setAttribute(
                "aria-label",
                "Close navigation"
            );


            sidebarOverlay.setAttribute(
                "aria-hidden",
                "false"
            );


            /*
             * Prevent the page behind the
             * sidebar from scrolling.
             */

            document.body.style.overflow =
                "hidden";

        }


        function closeSidebar() {

            if (
                !sidebar ||
                !sidebarOverlay ||
                !mobileMenuBtn
            ) {
                return;
            }


            sidebar.classList.remove(
                "mobile-open"
            );

            sidebarOverlay.classList.remove(
                "active"
            );

            mobileMenuBtn.classList.remove(
                "is-open"
            );


            mobileMenuBtn.setAttribute(
                "aria-expanded",
                "false"
            );

            mobileMenuBtn.setAttribute(
                "aria-label",
                "Open navigation"
            );


            sidebarOverlay.setAttribute(
                "aria-hidden",
                "true"
            );


            document.body.style.overflow =
                "";

        }


        function toggleSidebar() {

            if (
                sidebar &&
                sidebar.classList.contains(
                    "mobile-open"
                )
            ) {

                closeSidebar();

            } else {

                openSidebar();

            }

        }


        /* =====================================================
           BURGER / X BUTTON
           ===================================================== */

        if (mobileMenuBtn) {

            mobileMenuBtn.addEventListener(
                "click",
                toggleSidebar
            );

        }


        /* =====================================================
           CLICK OUTSIDE SIDEBAR
           ===================================================== */

        if (sidebarOverlay) {

            sidebarOverlay.addEventListener(
                "click",
                function () {

                    closeSidebar();

                }
            );

        }


        /* =====================================================
           SIDEBAR LINKS
           ===================================================== */

        sidebarLinks.forEach(
            function (link) {

                link.addEventListener(
                    "click",
                    function () {

                        /*
                         * On mobile, close the
                         * sidebar immediately.
                         *
                         * On desktop this has
                         * no visual effect.
                         */

                        closeSidebar();

                    }
                );

            }
        );


        /* =====================================================
           LOGOUT
           ===================================================== */

        if (
            logoutBtn &&
            logoutModal
        ) {

            logoutBtn.addEventListener(
                "click",
                function () {

                    closeSidebar();

                    logoutModal.classList.add(
                        "active"
                    );

                    document.body.style.overflow =
                        "hidden";

                }
            );

        }


        /* =====================================================
           CANCEL LOGOUT
           ===================================================== */

        if (
            cancelLogout &&
            logoutModal
        ) {

            cancelLogout.addEventListener(
                "click",
                function () {

                    logoutModal.classList.remove(
                        "active"
                    );

                    document.body.style.overflow =
                        "";

                }
            );

        }


        /* =====================================================
           CONFIRM LOGOUT
           ===================================================== */

        if (confirmLogout) {

            confirmLogout.addEventListener(
                "click",
                function () {

                    window.location.href =
                        "logout.php";

                }
            );

        }


        /* =====================================================
           DELETE MODAL
           ===================================================== */

        let deleteForm = null;


        const deleteForms =
            document.querySelectorAll(
                "[data-delete-form]"
            );


        deleteForms.forEach(
            function (form) {

                form.addEventListener(
                    "submit",
                    function (event) {

                        event.preventDefault();


                        deleteForm = form;


                        const deleteButton =
                            form.querySelector(
                                "[data-delete-user]"
                            );


                        if (
                            deleteButton &&
                            deleteMessage
                        ) {

                            const email =
                                deleteButton.getAttribute(
                                    "data-delete-user"
                                );


                            deleteMessage.textContent =
                                "Are you sure you want to delete " +
                                email +
                                "?";

                        }


                        if (deleteModal) {

                            deleteModal.classList.add(
                                "active"
                            );

                            document.body.style.overflow =
                                "hidden";

                        }

                    }
                );

            }
        );


        /* =====================================================
           CANCEL DELETE
           ===================================================== */

        if (
            cancelDelete &&
            deleteModal
        ) {

            cancelDelete.addEventListener(
                "click",
                function () {

                    deleteModal.classList.remove(
                        "active"
                    );

                    deleteForm = null;

                    document.body.style.overflow =
                        "";

                }
            );

        }


        /* =====================================================
           CONFIRM DELETE
           ===================================================== */

        if (confirmDelete) {

            confirmDelete.addEventListener(
                "click",
                function () {

                    if (deleteForm) {

                        deleteForm.submit();

                    }

                }
            );

        }


        /* =====================================================
           CLICK OUTSIDE LOGOUT MODAL
           ===================================================== */

        if (logoutModal) {

            logoutModal.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target ===
                        logoutModal
                    ) {

                        logoutModal.classList.remove(
                            "active"
                        );

                        document.body.style.overflow =
                            "";

                    }

                }
            );

        }


        /* =====================================================
           CLICK OUTSIDE DELETE MODAL
           ===================================================== */

        if (deleteModal) {

            deleteModal.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target ===
                        deleteModal
                    ) {

                        deleteModal.classList.remove(
                            "active"
                        );

                        deleteForm = null;

                        document.body.style.overflow =
                            "";

                    }

                }
            );

        }


        /* =====================================================
           PASSWORD SHOW / HIDE
           ===================================================== */

        if (
            passwordToggle &&
            passwordInput
        ) {

            passwordToggle.addEventListener(
                "click",
                function () {

                    const isPassword =
                        passwordInput.type ===
                        "password";


                    if (isPassword) {

                        passwordInput.type =
                            "text";


                        passwordToggle.innerHTML =
                            '<i class="fa-solid fa-eye-slash"></i>';


                        passwordToggle.setAttribute(
                            "aria-label",
                            "Hide password"
                        );

                    } else {

                        passwordInput.type =
                            "password";


                        passwordToggle.innerHTML =
                            '<i class="fa-solid fa-eye"></i>';


                        passwordToggle.setAttribute(
                            "aria-label",
                            "Show password"
                        );

                    }

                }
            );

        }


        /* =====================================================
           ACTIVE SIDEBAR PAGE
           ===================================================== */

        const currentPage =
            window.location.pathname
                .split("/")
                .pop();


        sidebarLinks.forEach(
            function (link) {

                const page =
                    link.getAttribute(
                        "data-page"
                    );


                if (
                    page ===
                    currentPage
                ) {

                    link.classList.add(
                        "active"
                    );

                } else {

                    link.classList.remove(
                        "active"
                    );

                }

            }
        );


        /* =====================================================
           ESCAPE KEY
           ===================================================== */

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key ===
                    "Escape"
                ) {

                    closeSidebar();


                    if (logoutModal) {

                        logoutModal.classList.remove(
                            "active"
                        );

                    }


                    if (deleteModal) {

                        deleteModal.classList.remove(
                            "active"
                        );

                        deleteForm = null;

                    }


                    document.body.style.overflow =
                        "";

                }

            }
        );


        /* =====================================================
           RESIZE
           ===================================================== */

        window.addEventListener(
            "resize",
            function () {

                /*
                 * When returning to desktop,
                 * force the mobile sidebar closed.
                 */

                if (
                    window.innerWidth >
                    768
                ) {

                    closeSidebar();

                }

            }
        );


    }
);