/* =========================================================
   KATES GOODIES
   REGISTRATION JAVASCRIPT
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const form =
        document.getElementById("registerForm");

    const fullName =
        document.getElementById("full_name");

    const username =
        document.getElementById("username");

    const email =
        document.getElementById("email");

    const password =
        document.getElementById("password");

    const confirmPassword =
        document.getElementById("confirm_password");


    const passwordToggle =
        document.getElementById("passwordToggle");

    const confirmPasswordToggle =
        document.getElementById(
            "confirmPasswordToggle"
        );


    /* =====================================================
       SAFETY CHECK
       ===================================================== */

    if (!form) {

        console.error(
            "Registration form not found."
        );

        return;

    }


    /* =====================================================
       PASSWORD TOGGLE FUNCTION
       ===================================================== */

    function setupPasswordToggle(
        button,
        input
    ) {

        if (!button || !input) {
            return;
        }


        button.addEventListener(
            "click",
            function () {

                if (
                    input.type === "password"
                ) {

                    input.type = "text";

                    button.innerHTML =
                        '<i class="fa-solid fa-eye-slash"></i>';

                    button.setAttribute(
                        "aria-label",
                        "Hide password"
                    );

                } else {

                    input.type = "password";

                    button.innerHTML =
                        '<i class="fa-solid fa-eye"></i>';

                    button.setAttribute(
                        "aria-label",
                        "Show password"
                    );

                }

            }
        );

    }


    /* =====================================================
       ENABLE PASSWORD TOGGLES
       ===================================================== */

    setupPasswordToggle(
        passwordToggle,
        password
    );


    setupPasswordToggle(
        confirmPasswordToggle,
        confirmPassword
    );


    /* =====================================================
       FORM VALIDATION
       ===================================================== */

    form.addEventListener(
        "submit",
        function (event) {

            const fullNameValue =
                fullName.value.trim();

            const usernameValue =
                username.value.trim();

            const emailValue =
                email.value.trim();

            const passwordValue =
                password.value;

            const confirmPasswordValue =
                confirmPassword.value;


            /* =============================================
               FULL NAME
               ============================================= */

            if (!fullNameValue) {

                event.preventDefault();

                alert(
                    "Please enter your full name."
                );

                fullName.focus();

                return;

            }


            /* =============================================
               USERNAME
               ============================================= */

            if (!usernameValue) {

                event.preventDefault();

                alert(
                    "Please enter your username."
                );

                username.focus();

                return;

            }


            if (
                !/^[a-zA-Z0-9_]{3,30}$/.test(
                    usernameValue
                )
            ) {

                event.preventDefault();

                alert(
                    "Username must contain 3 to 30 letters, numbers, or underscores only."
                );

                username.focus();

                return;

            }


            /* =============================================
               EMAIL
               ============================================= */

            if (!emailValue) {

                event.preventDefault();

                alert(
                    "Please enter your email address."
                );

                email.focus();

                return;

            }


            if (
                !email.validity.valid
            ) {

                event.preventDefault();

                alert(
                    "Please enter a valid email address."
                );

                email.focus();

                return;

            }


            /* =============================================
               PASSWORD
               ============================================= */

            if (!passwordValue) {

                event.preventDefault();

                alert(
                    "Please enter your password."
                );

                password.focus();

                return;

            }


            if (
                passwordValue.length < 8
            ) {

                event.preventDefault();

                alert(
                    "Password must be at least 8 characters long."
                );

                password.focus();

                return;

            }


            /* =============================================
               CONFIRM PASSWORD
               ============================================= */

            if (!confirmPasswordValue) {

                event.preventDefault();

                alert(
                    "Please confirm your password."
                );

                confirmPassword.focus();

                return;

            }


            if (
                passwordValue !==
                confirmPasswordValue
            ) {

                event.preventDefault();

                alert(
                    "Passwords do not match."
                );

                confirmPassword.focus();

                return;

            }


            /*
             * If everything is valid,
             * allow the PHP form submission.
             */

        }
    );

});