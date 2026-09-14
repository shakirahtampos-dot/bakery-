
document.addEventListener("DOMContentLoaded", function () {

    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const loginForm = document.getElementById("adminLoginForm");
    const submitButton = loginForm
        ? loginForm.querySelector(".btn-primary")
        : null;


   
    /* =====================================================
       FORM SUBMIT
       ===================================================== */

    if (loginForm) {

        loginForm.addEventListener("submit", function () {

            if (!loginForm.checkValidity()) {
                return;
            }

            if (submitButton) {

                submitButton.disabled = true;

                const buttonText =
                    submitButton.querySelector("span");

                if (buttonText) {
                    buttonText.textContent = "Signing In...";
                }

                const buttonIcon =
                    submitButton.querySelector("i");

                if (buttonIcon) {

                    buttonIcon.classList.remove(
                        "fa-right-to-bracket"
                    );

                    buttonIcon.classList.add(
                        "fa-spinner",
                        "fa-spin"
                    );

                }

            }

        });

    }


    /* =====================================================
       REMOVE ERROR MESSAGE AFTER USER STARTS TYPING
       ===================================================== */

    const errorMessage =
        document.querySelector(".message");

    if (errorMessage) {

        const inputs =
            loginForm.querySelectorAll("input");

        inputs.forEach(function (input) {

            input.addEventListener("input", function () {

                errorMessage.style.transition =
                    "opacity 0.3s ease";

                errorMessage.style.opacity = "0";

                setTimeout(function () {

                    if (errorMessage) {
                        errorMessage.style.display = "none";
                    }

                }, 300);

            });

        });

    }

});

