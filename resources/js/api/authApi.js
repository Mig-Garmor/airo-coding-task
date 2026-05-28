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
