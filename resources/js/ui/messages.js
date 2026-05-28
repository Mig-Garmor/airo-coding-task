const messageBox = document.getElementById("message");

export function showMessage(message, type = "success") {
    messageBox.textContent = message;
    messageBox.className = `message ${type}`;
}

export function clearMessage() {
    messageBox.textContent = "";
    messageBox.className = "message hidden";
}
