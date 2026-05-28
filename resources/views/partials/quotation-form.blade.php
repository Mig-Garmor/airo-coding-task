<section id="quotation-section" hidden>
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