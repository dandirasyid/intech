document.addEventListener("DOMContentLoaded", function () {
    const toggles = [
        { button: "#toggleLoginPassword", field: ".loginPassword" },
        { button: "#toggleRegisterPassword", field: ".registerPassword" },
        { button: "#toggleConfirmPassword", field: "#password_confirmation" },
    ];

    toggles.forEach(({ button, field }) => {
        const toggleButton = document.querySelector(button);
        const passwordField = document.querySelector(field);

        if (toggleButton && passwordField) {
            toggleButton.addEventListener("click", function () {
                const type =
                    passwordField.getAttribute("type") === "password"
                        ? "text"
                        : "password";
                passwordField.setAttribute("type", type);
                this.classList.toggle("bi-eye");
                this.classList.toggle("bi-eye-slash");
            });
        }
    });

    const confirmPasswordField = document.querySelector(
        "#password_confirmation"
    );
    const registerPasswordField = document.querySelector("#registerPassword");

    if (confirmPasswordField && registerPasswordField) {
        confirmPasswordField.addEventListener("input", function () {
            if (confirmPasswordField.value !== registerPasswordField.value) {
                confirmPasswordField.setCustomValidity("Password tidak cocok");
            } else {
                confirmPasswordField.setCustomValidity("");
            }
        });
    }
});