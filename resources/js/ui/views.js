const loginSection = document.getElementById("login-section");
const registerSection = document.getElementById("register-section");
const quotationSection = document.getElementById("quotation-section");

const loginForm = document.getElementById("login-form");
const registerForm = document.getElementById("register-form");
const quotationForm = document.getElementById("quotation-form");

const logoutButton = document.getElementById("logout-button");
const showRegisterButton = document.getElementById("show-register-button");
const showLoginButton = document.getElementById("show-login-button");

export function bindLoginForm(handleLogin) {
    loginForm.addEventListener("submit", handleLogin);
}

export function bindRegisterForm(handleRegister) {
    registerForm.addEventListener("submit", handleRegister);
}

export function bindQuotationForm(handleQuotation) {
    quotationForm.addEventListener("submit", handleQuotation);
}

export function bindLogoutButton(handleLogout) {
    logoutButton.addEventListener("click", handleLogout);
}

export function bindAuthToggleButtons() {
    showRegisterButton.addEventListener("click", showRegister);
    showLoginButton.addEventListener("click", showLogin);
}

function resetLoginForm() {
    loginForm.reset();
}

function resetRegisterForm() {
    registerForm.reset();
}

export function showLogin() {
    resetRegisterForm();

    loginSection.hidden = false;
    registerSection.hidden = true;
    quotationSection.hidden = true;
}

export function showRegister() {
    resetLoginForm();

    loginSection.hidden = true;
    registerSection.hidden = false;
    quotationSection.hidden = true;
}

export function showQuotation() {
    resetLoginForm();
    resetRegisterForm();

    loginSection.hidden = true;
    registerSection.hidden = true;
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
