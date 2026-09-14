(function () {

    "use strict";

    /* =========================================================
       KATES GOODIES
       CONTACT US PAGE JAVASCRIPT

       MOBILE BURGER NAVIGATION
       ACCOUNT DROPDOWN
       CONTACT FORM VALIDATION
       CONTACT FORM SUBMISSION
    ========================================================= */

    document.addEventListener("DOMContentLoaded", function () {

        /* =====================================================
           ELEMENTS
        ===================================================== */

        const mainNav = document.getElementById("mainNav");
        const navToggle = document.getElementById("navToggle");
        const navClose = document.getElementById("navClose");
        const navOverlay = document.getElementById("navOverlay");

        const accountButton =
            document.getElementById("accountButton");

        const accountDropdown =
            document.getElementById("accountDropdown");

        const accountContainer =
            document.querySelector(".account-container") ||
            document.querySelector(".account-wrapper");


        /* =====================================================
           MOBILE NAVIGATION
           BURGER BUTTON OPENS THE MENU
           X BUTTON / OVERLAY / ESCAPE CLOSE IT
        ===================================================== */

        function openMobileNav() {

            if (!mainNav || !navToggle) {
                return;
            }

            mainNav.classList.add("open");

            navToggle.classList.add("active");

            if (navOverlay) {
                navOverlay.classList.add("show");
                navOverlay.setAttribute("aria-hidden", "false");
            }

            document.body.classList.add("nav-open");

            navToggle.setAttribute("aria-expanded", "true");

            navToggle.setAttribute(
                "aria-label",
                "Close navigation"
            );

        }


        function closeMobileNav() {

            if (!mainNav || !navToggle) {
                return;
            }

            mainNav.classList.remove("open");

            navToggle.classList.remove("active");

            if (navOverlay) {
                navOverlay.classList.remove("show");
                navOverlay.setAttribute("aria-hidden", "true");
            }

            document.body.classList.remove("nav-open");

            navToggle.setAttribute("aria-expanded", "false");

            navToggle.setAttribute(
                "aria-label",
                "Open navigation"
            );

        }


        function toggleMobileNav() {

            if (!mainNav) {
                return;
            }

            if (mainNav.classList.contains("open")) {
                closeMobileNav();
            } else {
                openMobileNav();
            }

        }


        /* =====================================================
           BURGER BUTTON
        ===================================================== */

        if (navToggle) {

            navToggle.addEventListener("click", function (event) {

                event.preventDefault();
                event.stopPropagation();

                toggleMobileNav();

            });

        }


        /* =====================================================
           CLOSE (X) BUTTON
        ===================================================== */

        if (navClose) {

            navClose.addEventListener("click", function (event) {

                event.preventDefault();
                event.stopPropagation();

                closeMobileNav();

            });

        }


        /* =====================================================
           OVERLAY
        ===================================================== */

        if (navOverlay) {

            navOverlay.addEventListener("click", function () {

                closeMobileNav();

            });

        }


        /* =====================================================
           NAVIGATION LINKS
        ===================================================== */

        if (mainNav) {

            const navLinks =
                mainNav.querySelectorAll("a");

            navLinks.forEach(function (link) {

                link.addEventListener("click", function () {

                    closeMobileNav();

                });

            });

        }


        /* =====================================================
           ESCAPE KEY
        ===================================================== */

        document.addEventListener("keydown", function (event) {

            if (event.key === "Escape") {

                closeMobileNav();
                closeAccountDropdown();

            }

        });


        /* =====================================================
           RESET MOBILE MENU ON DESKTOP
        ===================================================== */

        window.addEventListener("resize", function () {

            if (window.innerWidth > 800) {

                closeMobileNav();

            }

        });


        /* =====================================================
           ACCOUNT DROPDOWN
        ===================================================== */

        function openAccountDropdown() {

            if (!accountDropdown || !accountButton) {
                return;
            }

            accountDropdown.classList.add("show");

            accountButton.setAttribute(
                "aria-expanded",
                "true"
            );

        }


        function closeAccountDropdown() {

            if (!accountDropdown || !accountButton) {
                return;
            }

            accountDropdown.classList.remove("show");

            accountButton.setAttribute(
                "aria-expanded",
                "false"
            );

        }


        function toggleAccountDropdown(event) {

            if (!accountDropdown || !accountButton) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            if (accountDropdown.classList.contains("show")) {

                closeAccountDropdown();

            } else {

                openAccountDropdown();

            }

        }


        if (accountButton) {

            accountButton.addEventListener(
                "click",
                toggleAccountDropdown
            );

        }


        /* =====================================================
           CLOSE ACCOUNT DROPDOWN WHEN CLICKING OUTSIDE
        ===================================================== */

        document.addEventListener("click", function (event) {

            if (
                accountContainer &&
                !accountContainer.contains(event.target)
            ) {

                closeAccountDropdown();

            }

        });


        /* =====================================================
           CONTACT FORM
        ===================================================== */

        const contactForm =
            document.getElementById("contactForm");

        if (!contactForm) {
            return;
        }

        const nameInput =
            document.getElementById("contactName");

        const emailInput =
            document.getElementById("contactEmail");

        const subjectInput =
            document.getElementById("contactSubject");

        const messageInput =
            document.getElementById("contactMessage");

        const formStatus =
            document.getElementById("formStatus");

        const submitButton =
            contactForm.querySelector(".send-message-button");


        /* =====================================================
           FORM FIELDS
        ===================================================== */

        const fields = [

            {
                input: nameInput,
                errorEl: document.getElementById("nameError"),

                validate: function (value) {

                    return value.trim().length > 0
                        ? ""
                        : "Please enter your name.";

                }
            },

            {
                input: emailInput,
                errorEl: document.getElementById("emailError"),

                validate: function (value) {

                    const emailPattern =
                        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (value.trim().length === 0) {
                        return "Please enter your email.";
                    }

                    return emailPattern.test(value.trim())
                        ? ""
                        : "Please enter a valid email address.";

                }
            },

            {
                input: subjectInput,
                errorEl: document.getElementById("subjectError"),

                validate: function (value) {

                    return value.trim().length > 0
                        ? ""
                        : "Please enter a subject.";

                }
            },

            {
                input: messageInput,
                errorEl: document.getElementById("messageError"),

                validate: function (value) {

                    return value.trim().length > 0
                        ? ""
                        : "Please enter your message.";

                }
            }

        ];


        /* =====================================================
           SHOW FIELD ERROR
        ===================================================== */

        function showFieldError(field, message) {

            if (field.errorEl) {

                field.errorEl.textContent = message;

            }

            if (field.input) {

                field.input.classList.toggle(
                    "input-error",
                    Boolean(message)
                );

            }

        }


        /* =====================================================
           VALIDATE FIELD
        ===================================================== */

        function validateField(field) {

            if (!field.input) {
                return false;
            }

            const message =
                field.validate(field.input.value);

            showFieldError(field, message);

            return message === "";

        }


        /* =====================================================
           LIVE VALIDATION
        ===================================================== */

        fields.forEach(function (field) {

            if (!field.input) {
                return;
            }

            field.input.addEventListener("blur", function () {

                validateField(field);

            });

            field.input.addEventListener("input", function () {

                if (
                    field.input.classList.contains("input-error")
                ) {

                    validateField(field);

                }

            });

        });


        /* =====================================================
           FORM STATUS
        ===================================================== */

        function setStatus(message, type) {

            if (!formStatus) {
                return;
            }

            formStatus.textContent = message;

            formStatus.classList.remove(
                "success",
                "error"
            );

            if (type) {

                formStatus.classList.add(type);

            }

        }


        /* =====================================================
           FORM SUBMIT
        ===================================================== */

        contactForm.addEventListener("submit", function (event) {

            event.preventDefault();

            let isFormValid = true;

            fields.forEach(function (field) {

                if (!validateField(field)) {

                    isFormValid = false;

                }

            });

            if (!isFormValid) {

                setStatus(
                    "Please fix the errors above before sending.",
                    "error"
                );

                return;

            }

            if (submitButton) {

                submitButton.disabled = true;
                submitButton.textContent = "SENDING...";

            }

            setStatus("", "");

            const formData =
                new FormData(contactForm);

            fetch("contact_us.php", {

                method: "POST",

                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },

                body: formData

            })

                .then(function (response) {

                    return response.json().catch(function () {

                        return {
                            success: response.ok
                        };

                    });

                })

                .then(function (result) {

                    if (result && result.success) {

                        setStatus(
                            "Thank you! Your message has been sent.",
                            "success"
                        );

                        contactForm.reset();

                        fields.forEach(function (field) {

                            showFieldError(field, "");

                        });

                    } else {

                        setStatus(
                            (result && result.message) ||
                            "Something went wrong. Please try again.",
                            "error"
                        );

                    }

                })

                .catch(function () {

                    setStatus(
                        "Unable to send your message right now. Please try again later.",
                        "error"
                    );

                })

                .finally(function () {

                    if (submitButton) {

                        submitButton.disabled = false;
                        submitButton.textContent = "SEND MESSAGE";

                    }

                });

        });

    });

})();