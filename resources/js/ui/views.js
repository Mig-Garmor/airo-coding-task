const loginSection = document.getElementById("login-section");
const quotationSection = document.getElementById("quotation-section");
const loginForm = document.getElementById("login-form");
const quotationForm = document.getElementById("quotation-form");
const logoutButton = document.getElementById("logout-button");

export function bindLoginForm(handleLogin) {
    loginForm.addEventListener("submit", handleLogin);
}

export function bindQuotationForm(handleQuotation) {
    quotationForm.addEventListener("submit", handleQuotation);
}

export function bindLogoutButton(handleLogout) {
    logoutButton.addEventListener("click", handleLogout);
}

export function showLogin() {
    loginSection.hidden = false;
    quotationSection.hidden = true;
}

export function showQuotation() {
    loginSection.hidden = true;
    quotationSection.hidden = false;
}

export function clearQuotationResult() {
    const resultElement = document.getElementById("quotation-result");

    if (resultElement) {
        resultElement.textContent = "";
    }
}

export function showQuotationResult(data) {
    const resultElement = document.getElementById("quotation-result");

    if (resultElement) {
        resultElement.textContent = JSON.stringify(data, null, 2);
    }
}
