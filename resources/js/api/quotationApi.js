export async function createQuotation(token, payload) {
    const response = await fetch("/quotation", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(payload),
    });

    const data = await response.json();

    return { response, data };
}
