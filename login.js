// Example: Validate form inputs before submission
document.getElementById('login-form').addEventListener('submit', function (e) {
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();
    let errorMessage = '';

    // Simple validation
    if (!email || !password) {
        errorMessage = 'Both fields are required.';
    }

    // Prevent form submission if there is an error
    if (errorMessage) {
        e.preventDefault();
        alert(errorMessage);
    }
});

// Optional: Toggle password visibility
document.getElementById('password').addEventListener('focus', function () {
    this.type = 'text';
});
document.getElementById('password').addEventListener('blur', function () {
    this.type = 'password';
});
