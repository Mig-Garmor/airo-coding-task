const tokenStorageKey = "airo_jwt_token";

export function getToken() {
    return localStorage.getItem(tokenStorageKey);
}

export function setToken(token) {
    localStorage.setItem(tokenStorageKey, token);
}

export function clearToken() {
    localStorage.removeItem(tokenStorageKey);
}
