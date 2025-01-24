<div class="modal" id="submitModal">
    <div class="form-container">
        <h1>Submit Your Referral Code</h1>
        <form action="store_code.php" method="POST">
            <div class="input-wrapper">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="input-wrapper">
                <label for="referralCode">Referral Code</label>
                <input 
                    type="text" 
                    id="referralCode" 
                    name="referralCode" 
                    placeholder="Ex: ZAK1452284"
                    pattern="(?=(?:.*[A-Za-z]){3,})(?=(?:.*\d){7,})[A-Za-z0-9]+"
                    title="Code must contain at least 3 letters and 7 numbers"
                    required
                >
            </div>

            <button type="submit">Submit Code</button>
            <button type="button" class="modal-close" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<div class="toast" id="toast"></div>