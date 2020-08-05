const pages = [...document.querySelectorAll("[data-page]")];
const buttons = document.querySelectorAll("[data-to-page]");
const registerForm = document.querySelector("#register-form");
const emailField = registerForm.querySelector("#email-field");
const mojangField = registerForm.querySelector("#mojang-field");
const emailMessageElement = registerForm.querySelector("#email-message");
const mojangMessageElement = registerForm.querySelector("#mojang-message");
const formMessage = registerForm.querySelector("#form-message");

const goToPage = currentPage => {
    if(currentPage === null || typeof currentPage === "undefined") return;

    const previousPage = document.querySelector(".current");

    if(currentPage.classList.contains("left")) {
        previousPage.classList.add("right");
    } else if(currentPage.classList.contains("right")) {
        previousPage.classList.add("left");
    }

    previousPage.classList.remove("current")
    currentPage.classList.remove("left");
    currentPage.classList.remove("right");
    currentPage.classList.add("current");
}

const getEmailStatus = async () =>  {
    return $.ajax({
        type: "POST",
        url: "./get-email-status.php",
        data: {
            email: emailField.value
        }
    });
}

const insertUser = async () => {
    return $.ajax({
        type: "POST",
        url: "./insert-user.php",
        data: {
            email: emailField.value,
            mojang: mojangField.value
        }
    });
}

const resendEmail = async () => {
    return $.ajax({
        type: "POST",
        url: "./resend-email.php",
        data: {
            email: emailField.value
        }
    }); 
}

buttons.forEach(button => {
    button.addEventListener("click", () => {
        const toPage = button.dataset.toPage;
        const page = pages.find(page => page.dataset.page === toPage);
        goToPage(page);
    });
});

registerForm.addEventListener("submit", async e => {
    e.preventDefault();
    const emailStatus = JSON.parse(await getEmailStatus());

    emailMessageElement.classList.remove("error", "success");
    emailMessageElement.innerHTML = "";
    mojangMessageElement.classList.remove("error", "success");
    mojangMessageElement.innerHTML = "";
    formMessage.classList.remove("error", "success");
    formMessage.innerHTML = "";

    if(emailStatus.exists) {
        emailMessageElement.classList.add("error");
        emailMessageElement.innerHTML = "Username already registered";
        if(emailStatus.active) {
            emailMessageElement.innerHTML += " and activated.";
            return;
        }
        emailMessageElement.innerHTML += " but you need to activate it.";

        const resendStatus = JSON.parse(await resendEmail());
        if(resendStatus.updated) {
            emailMessageElement.innerHTML += " <span class='success'>Sent new verification email.</span>";
        } else {
            emailMessageElement.innerHTML += " Failed to send new verification email.";
        }
    } else {
        const insertStatus = JSON.parse(await insertUser());
        const {email, mojang} = insertStatus.errors;
        
        emailMessageElement.innerHTML = typeof email === "undefined" ? "" : email;
        mojangMessageElement.innerHTML = typeof mojang === "undefined" ? "" : mojang;

        if(insertStatus.errors.length === 0) {
            formMessage.innerHTML = insertStatus.message;
        }
        
        if(insertStatus.mailed) {
            emailMessageElement.classList.add("success");
            mojangMessageElement.classList.add("success");
            formMessage.classList.add("success");
            formMessage.innerHTML = "Success! Sent verification mail.";
        } else {
            emailMessageElement.classList.add("error");
            mojangMessageElement.classList.add("error");
            formMessage.classList.add("error");
            formMessage.innerHTML = "Error! Could not send verification mail.";
        }
    }
});