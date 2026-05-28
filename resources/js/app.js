import { getToken, setToken, clearToken } from "./auth/tokenStorage";
import { login, register, fetchCurrentUser } from "./api/authApi";
import { createQuotation } from "./api/quotationApi";
import { showMessage, clearMessage } from "./ui/messages";
import {
    bindLoginForm,
    bindRegisterForm,
    bindQuotationForm,
    bindLogoutButton,
    bindAuthToggleButtons,
    showLogin,
    showQuotation,
    clearQuotationResult,
    showQuotationResult,
} from "./ui/views";
import { formatErrorResponse } from "./utils/errors";

async function handleRegister(event) {
    event.preventDefault();
    clearMessage();

    const formData = new FormData(event.target);

    try {
        const { response, data } = await register(
            formData.get("name"),
            formData.get("email"),
            formData.get("password"),
            formData.get("password_confirmation"),
        );

        if (!response.ok) {
            showMessage(formatErrorResponse(data), "error");
            return;
        }

        setToken(data.token);
        showQuotation();
        showMessage("Account created successfully.");
    } catch (error) {
        showMessage("Unable to create account. Please try again.", "error");
    }
}

async function handleLogin(event) {
    event.preventDefault();
    clearMessage();

    const formData = new FormData(event.target);

    try {
        const { response, data } = await login(
            formData.get("email"),
            formData.get("password"),
        );

        if (!response.ok) {
            showMessage(formatErrorResponse(data), "error");
            return;
        }

        setToken(data.token);
        showQuotation();
        showMessage("Login successful.");
    } catch (error) {
        showMessage("Unable to login. Please try again.", "error");
    }
}

async function handleQuotation(event) {
    event.preventDefault();
    clearMessage();
    clearQuotationResult();

    const token = getToken();

    if (!token) {
        showLogin();
        showMessage("Please login before creating a quotation.", "error");
        return;
    }

    const formData = new FormData(event.target);

    try {
        const { response, data } = await createQuotation(token, {
            age: formData.get("age"),
            currency_id: formData.get("currency_id"),
            start_date: formData.get("start_date"),
            end_date: formData.get("end_date"),
        });

        if (response.status === 401) {
            clearToken();
            showLogin();
            showMessage("Session expired. Please login again.", "error");
            return;
        }

        if (!response.ok) {
            showMessage(formatErrorResponse(data), "error");
            return;
        }

        showQuotationResult(data);
        showMessage("Quotation created successfully.");
    } catch (error) {
        showMessage("Unable to create quotation. Please try again.", "error");
    }
}

function handleLogout() {
    clearToken();
    clearMessage();
    clearQuotationResult();
    showLogin();
    showMessage("Logged out.");
}

async function initializeApp() {
    bindLoginForm(handleLogin);
    bindRegisterForm(handleRegister);
    bindQuotationForm(handleQuotation);
    bindLogoutButton(handleLogout);
    bindAuthToggleButtons();

    const token = getToken();

    if (!token) {
        showLogin();
        return;
    }

    try {
        const response = await fetchCurrentUser(token);

        if (!response.ok) {
            clearToken();
            showLogin();
            return;
        }

        showQuotation();
    } catch (error) {
        clearToken();
        showLogin();
    }
}

initializeApp();
