export async function register(name, email, password, passwordConfirmation) {
    const response = await fetch("/register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({
            name,
            email,
            password,
            password_confirmation: passwordConfirmation,
        }),
    });

    const data = await response.json();

    return { response, data };
}

export async function login(email, password) {
    const response = await fetch("/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({ email, password }),
    });

    const data = await response.json();

    return { response, data };
}

export async function fetchCurrentUser(token) {
    return fetch("/me", {
        headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        },
    });
}
