export function formatErrorResponse(data) {
    if (data.errors) {
        return Object.values(data.errors).flat().join("\n");
    }

    return data.message || data.error || "An unexpected error occurred.";
}
