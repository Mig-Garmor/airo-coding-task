<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIRO Quotation</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 720px;
            margin: 40px auto;
            padding: 0 16px;
        }

        form {
            margin-bottom: 24px;
            padding: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 12px;
        }

        input,
        select,
        button {
            margin-top: 4px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            margin-top: 16px;
            cursor: pointer;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            white-space: pre-wrap;
        }

        .hidden {
            display: none;
        }

        .success {
            background: #e8f7e8;
            border: 1px solid #9fd49f;
        }

        .error {
            background: #fdeaea;
            border: 1px solid #e6a1a1;
        }

        pre {
            padding: 16px;
            background: #f5f5f5;
            border-radius: 8px;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <h1>Quotation Calculator</h1>

    <div id="message" class="message hidden"></div>

    <main id="app"></main>

    <script>
        const tokenStorageKey = 'airo_jwt_token';

        const app = document.getElementById('app');
        const messageBox = document.getElementById('message');

        function getToken() {
            return localStorage.getItem(tokenStorageKey);
        }

        function setToken(token) {
            localStorage.setItem(tokenStorageKey, token);
        }

        function clearToken() {
            localStorage.removeItem(tokenStorageKey);
        }

        function showMessage(message, type = 'success') {
            messageBox.textContent = message;
            messageBox.className = `message ${type}`;
        }

        function clearMessage() {
            messageBox.textContent = '';
            messageBox.className = 'message hidden';
        }

        function formatErrorResponse(data) {
            if (data.errors) {
                return Object.values(data.errors).flat().join('\n');
            }

            return data.message || data.error || 'An unexpected error occurred.';
        }

        function renderLogin() {
            app.innerHTML = `
                <section id="login-section">
                    <h2>Login</h2>

                    <form id="login-form">
                        <label for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="reviewer@example.com"
                            required
                        >

                        <label for="password">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            value="password123"
                            required
                        >

                        <button type="submit">Login</button>
                    </form>
                </section>
            `;

            document.getElementById('login-form').addEventListener('submit', handleLogin);
        }

        function renderQuotation() {
            app.innerHTML = `
                <section id="quotation-section">
                    <button id="logout-button" type="button">Logout</button>

                    <h2>Create Quotation</h2>

                    <form id="quotation-form">
                        <label for="age">Ages</label>
                        <input
                            id="age"
                            name="age"
                            type="text"
                            value="28,35"
                            placeholder="28,35"
                            required
                        >

                        <label for="currency_id">Currency</label>
                        <select id="currency_id" name="currency_id" required>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                            <option value="USD">USD</option>
                        </select>

                        <label for="start_date">Start Date</label>
                        <input
                            id="start_date"
                            name="start_date"
                            type="date"
                            value="2020-10-01"
                            required
                        >

                        <label for="end_date">End Date</label>
                        <input
                            id="end_date"
                            name="end_date"
                            type="date"
                            value="2020-10-30"
                            required
                        >

                        <button type="submit">Create Quotation</button>
                    </form>

                    <h2>Result</h2>
                    <pre id="quotation-result"></pre>
                </section>
            `;

            document.getElementById('quotation-form').addEventListener('submit', handleQuotation);
            document.getElementById('logout-button').addEventListener('click', handleLogout);
        }

        async function handleLogin(event) {
            event.preventDefault();
            clearMessage();

            const formData = new FormData(event.target);

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        email: formData.get('email'),
                        password: formData.get('password'),
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    showMessage(formatErrorResponse(data), 'error');
                    return;
                }

                setToken(data.token);
                renderQuotation();
                showMessage('Login successful.');
            } catch (error) {
                showMessage('Unable to login. Please try again.', 'error');
            }
        }

        async function handleQuotation(event) {
            event.preventDefault();
            clearMessage();

            const resultElement = document.getElementById('quotation-result');
            resultElement.textContent = '';

            const token = getToken();

            if (!token) {
                renderLogin();
                showMessage('Please login before creating a quotation.', 'error');
                return;
            }

            const formData = new FormData(event.target);

            try {
                const response = await fetch('/quotation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                        age: formData.get('age'),
                        currency_id: formData.get('currency_id'),
                        start_date: formData.get('start_date'),
                        end_date: formData.get('end_date'),
                    }),
                });

                const data = await response.json();

                if (response.status === 401) {
                    clearToken();
                    renderLogin();
                    showMessage('Session expired. Please login again.', 'error');
                    return;
                }

                if (!response.ok) {
                    showMessage(formatErrorResponse(data), 'error');
                    return;
                }

                resultElement.textContent = JSON.stringify(data, null, 2);
                showMessage('Quotation created successfully.');
            } catch (error) {
                showMessage('Unable to create quotation. Please try again.', 'error');
            }
        }

        function handleLogout() {
            clearToken();
            clearMessage();
            renderLogin();
            showMessage('Logged out.');
        }

        async function initializeApp() {
            const token = getToken();

            if (!token) {
                renderLogin();
                return;
            }

            try {
                const response = await fetch('/me', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });

                if (!response.ok) {
                    clearToken();
                    renderLogin();
                    return;
                }

                renderQuotation();
            } catch (error) {
                clearToken();
                renderLogin();
            }
        }

        initializeApp();
    </script>
</body>
</html>