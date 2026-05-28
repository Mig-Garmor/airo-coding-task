<section id="register-section" hidden>
    <h2>Sign Up</h2>

    <form id="register-form">
        <label for="register-name">Name</label>
        <input
            id="register-name"
            name="name"
            type="text"
            required
        >

        <label for="register-email">Email</label>
        <input
            id="register-email"
            name="email"
            type="email"
            required
        >

        <label for="register-password">Password</label>
        <input
            id="register-password"
            name="password"
            type="password"
            required
        >

        <label for="register-password-confirmation">Confirm Password</label>
        <input
            id="register-password-confirmation"
            name="password_confirmation"
            type="password"
            required
        >

        <button type="submit">Sign Up</button>
    </form>

    <p>
        Already have an account?
        <button type="button" id="show-login-button">Login</button>
    </p>
</section>