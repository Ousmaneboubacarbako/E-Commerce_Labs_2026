const registerForm = document.getElementById("registerForm");

if (registerForm) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[0-9+\-\s]{6,15}$/;
    const fields = [
        { id: "customer_name", message: "Please enter your name." },
        { id: "customer_email", message: "Please enter a valid email address.", regex: emailRegex },
        { id: "customer_pass", message: "Please enter a password." },
        { id: "customer_country", message: "Please enter your country." },
        { id: "customer_city", message: "Please enter your city." },
        { id: "customer_contact", message: "Enter a valid contact number (7 to 15 characters).", regex: phoneRegex }
    ];

    registerForm.addEventListener("submit", function (event) {
        event.preventDefault();

        let firstInvalidField = null;

        fields.forEach(function (item) {
            const field = document.getElementById(item.id);
            const errorMessage = document.getElementById(item.id + "_error");
            const value = field.value.trim();
            const isInvalid = value === "" || (item.regex && !item.regex.test(value));

            errorMessage.textContent = isInvalid ? item.message : "";
            field.setAttribute("aria-invalid", isInvalid ? "true" : "false");

            if (isInvalid && firstInvalidField === null) {
                firstInvalidField = field;
            }
        });

        if (firstInvalidField) {
            firstInvalidField.focus();
            document.getElementById("formMessage").textContent = "Please correct the highlighted fields.";
            return;
        }

        document.getElementById("formMessage").textContent = "";
        registerCustomer();
    });
}
