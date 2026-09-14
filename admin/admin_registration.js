/* =========================================================
   KATES GOODIES
   ADMIN REGISTRATION JAVASCRIPT
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("adminRegistrationForm");

    const fullname = document.getElementById("fullname");
    const email = document.getElementById("email");
    const username = document.getElementById("username");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const terms = document.getElementById("terms");

    const passwordToggle =
        document.getElementById("passwordToggle");

    const confirmPasswordToggle =
        document.getElementById("confirmPasswordToggle");

    const message =
        document.getElementById("formMessage");


    /* =====================================================
       PASSWORD SHOW / HIDE
       ===================================================== */

    function setupPasswordToggle(toggleButton, input) {

        if (!toggleButton || !input) {
            return;
        }

        toggleButton.addEventListener("click", function () {

            if (input.type === "password") {

                input.type = "text";

                toggleButton.innerHTML =
                    '<i class="fa-solid fa-eye-slash"></i>';

                toggleButton.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                input.type = "password";

                toggleButton.innerHTML =
                    '<i class="fa-solid fa-eye"></i>';

                toggleButton.setAttribute(
                    "aria-label",
                    "Show password"
                );
            }

        });

    }


    setupPasswordToggle(
        passwordToggle,
        password
    );


    setupPasswordToggle(
        confirmPasswordToggle,
        confirmPassword
    );


    /* =====================================================
       HELPER FUNCTIONS
       ===================================================== */

    function setError(input, errorId, text) {

        const wrapper = input.closest(".input-wrapper");
        const error = document.getElementById(errorId);

        if (wrapper) {
            wrapper.classList.remove("valid");
            wrapper.classList.add("invalid");
        }

        if (error) {
            error.textContent = text;
        }
    }


    function setValid(input, errorId) {

        const wrapper = input.closest(".input-wrapper");
        const error = document.getElementById(errorId);

        if (wrapper) {
            wrapper.classList.remove("invalid");
            wrapper.classList.add("valid");
        }

        if (error) {
            error.textContent = "";
        }
    }


    function clearValidation(input, errorId) {

        const wrapper = input.closest(".input-wrapper");
        const error = document.getElementById(errorId);

        if (wrapper) {
            wrapper.classList.remove("invalid");
            wrapper.classList.remove("valid");
        }

        if (error) {
            error.textContent = "";
        }
    }


    function showMessage(text, type) {

        if (!message) {
            return;
        }

        message.textContent = text;

        message.className =
            "form-message " + type;
    }


    function clearMessage() {

        if (!message) {
            return;
        }

        message.textContent = "";

        message.className =
            "form-message";
    }


    /* =====================================================
       FULL NAME VALIDATION
       ===================================================== */

    function validateFullname() {

        const value =
            fullname.value.trim();

        if (value === "") {

            setError(
                fullname,
                "fullnameError",
                "Please enter your full name."
            );

            return false;
        }


        if (value.length < 3) {

            setError(
                fullname,
                "fullnameError",
                "Full name must be at least 3 characters."
            );

            return false;
        }


        setValid(
            fullname,
            "fullnameError"
        );

        return true;
    }


    /* =====================================================
       EMAIL VALIDATION
       ===================================================== */

    function validateEmail() {

        const value =
            email.value.trim();

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (value === "") {

            setError(
                email,
                "emailError",
                "Please enter your email address."
            );

            return false;
        }


        if (!emailPattern.test(value)) {

            setError(
                email,
                "emailError",
                "Please enter a valid email address."
            );

            return false;
        }


        setValid(
            email,
            "emailError"
        );

        return true;
    }


    /* =====================================================
       USERNAME VALIDATION
       ===================================================== */

    function validateUsername() {

        const value =
            username.value.trim();

        const usernamePattern =
            /^[a-zA-Z0-9._-]+$/;


        if (value === "") {

            setError(
                username,
                "usernameError",
                "Please enter a username."
            );

            return false;
        }


        if (value.length < 4) {

            setError(
                username,
                "usernameError",
                "Username must be at least 4 characters."
            );

            return false;
        }


        if (!usernamePattern.test(value)) {

            setError(
                username,
                "usernameError",
                "Use only letters, numbers, dots, underscores, or hyphens."
            );

            return false;
        }


        setValid(
            username,
            "usernameError"
        );

        return true;
    }


    /* =====================================================
       PASSWORD VALIDATION
       ===================================================== */

    function validatePassword() {

        const value =
            password.value;


        if (value === "") {

            setError(
                password,
                "passwordError",
                "Please enter a password."
            );

            return false;
        }


        if (value.length < 8) {

            setError(
                password,
                "passwordError",
                "Password must be at least 8 characters."
            );

            return false;
        }


        setValid(
            password,
            "passwordError"
        );

        return true;
    }


    /* =====================================================
       CONFIRM PASSWORD VALIDATION
       ===================================================== */

    function validateConfirmPassword() {

        const value =
            confirmPassword.value;


        if (value === "") {

            setError(
                confirmPassword,
                "confirmPasswordError",
                "Please confirm your password."
            );

            return false;
        }


        if (value !== password.value) {

            setError(
                confirmPassword,
                "confirmPasswordError",
                "Passwords do not match."
            );

            return false;
        }


        setValid(
            confirmPassword,
            "confirmPasswordError"
        );

        return true;
    }


    /* =====================================================
       TERMS VALIDATION
       ===================================================== */

    function validateTerms() {

        const error =
            document.getElementById("termsError");


        if (!terms.checked) {

            if (error) {
                error.textContent =
                    "You must agree to the Terms and Conditions.";
            }

            return false;
        }


        if (error) {
            error.textContent = "";
        }

        return true;
    }


    /* =====================================================
       LIVE VALIDATION
       ===================================================== */

    fullname.addEventListener(
        "blur",
        validateFullname
    );


    email.addEventListener(
        "blur",
        validateEmail
    );


    username.addEventListener(
        "blur",
        validateUsername
    );


    password.addEventListener(
        "blur",
        validatePassword
    );


    confirmPassword.addEventListener(
        "blur",
        validateConfirmPassword
    );


    password.addEventListener(
        "input",
        function () {

            if (confirmPassword.value !== "") {

                validateConfirmPassword();

            }

        }
    );


    terms.addEventListener(
        "change",
        validateTerms
    );


    /* =====================================================
       CLEAR MESSAGE WHEN USER STARTS TYPING
       ===================================================== */

    [
        fullname,
        email,
        username,
        password,
        confirmPassword
    ].forEach(function (input) {

        input.addEventListener(
            "input",
            function () {

                clearMessage();

            }
        );

    });


    /* =====================================================
       FORM SUBMIT
       ===================================================== */

    form.addEventListener(
        "submit",
        function (event) {

            /*
             * IMPORTANT:
             *
             * We DO NOT call event.preventDefault()
             * here when validation succeeds.
             *
             * This allows the form to submit normally
             * to admin_registration.php.
             */

            clearMessage();


            const validFullname =
                validateFullname();

            const validEmail =
                validateEmail();

            const validUsername =
                validateUsername();

            const validPassword =
                validatePassword();

            const validConfirmPassword =
                validateConfirmPassword();

            const validTerms =
                validateTerms();


            /* =================================================
               STOP SUBMISSION IF VALIDATION FAILED
               ================================================= */

            if (
                !validFullname ||
                !validEmail ||
                !validUsername ||
                !validPassword ||
                !validConfirmPassword ||
                !validTerms
            ) {

                /*
                 * Only prevent submission when
                 * the form is invalid.
                 */

                event.preventDefault();

                showMessage(
                    "Please correct the highlighted fields before creating your account.",
                    "error"
                );

                return;
            }


            

        }
    );

});
