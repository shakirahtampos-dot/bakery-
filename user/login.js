/* =========================================================
   KATES GOODIES
   LOGIN JAVASCRIPT
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("loginForm");

    const username = document.getElementById("username");

    const password = document.getElementById("password");

    const passwordToggle =
        document.getElementById("passwordToggle");


    /* =====================================================
       SAFETY CHECK
       ===================================================== */

    if (!form) {

        console.error(
            "Login form not found."
        );

        return;

    }


    /* =====================================================
       PASSWORD SHOW / HIDE
       ===================================================== */

    if (passwordToggle && password) {

        passwordToggle.addEventListener(
            "click",
            function () {

                if (password.type === "password") {

                    password.type = "text";

                    passwordToggle.innerHTML =
                        '<i class="fa-solid fa-eye-slash"></i>';

                    passwordToggle.setAttribute(
                        "aria-label",
                        "Hide password"
                    );

                } else {

                    password.type = "password";

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
       CLIENT-SIDE VALIDATION
       ===================================================== */

    form.addEventListener(
        "submit",
        function (event) {

            const usernameValue =
                username.value.trim();

            const passwordValue =
                password.value;


            /* Username */

            if (!usernameValue) {

                event.preventDefault();

                alert(
                    "Please enter your username."
                );

                username.focus();

                return;

            }


            /* Password */

            if (!passwordValue) {

                event.preventDefault();

                alert(
                    "Please enter your password."
                );

                password.focus();

                return;

            }

        }
    );

});