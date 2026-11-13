// Password strength indicator
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('customer_pass');
    
    if (passwordInput) {
        // Create strength indicator
        const strengthIndicator = document.createElement('div');
        strengthIndicator.id = 'password-strength';
        strengthIndicator.className = 'password-strength';
        strengthIndicator.innerHTML = `
            <div class="strength-bar">
                <div class="strength-bar-fill" id="strength-bar-fill"></div>
            </div>
            <span class="strength-text" id="strength-text"></span>
        `;
        
        // Insert after password input
        passwordInput.parentNode.insertBefore(strengthIndicator, passwordInput.nextSibling);
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            updateStrengthIndicator(strength);
        });
    }
});

function checkPasswordStrength(password) {
    let strength = 0;
    const checks = {
        length: password.length >= 8,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        number: /\d/.test(password),
        special: /[@$!%*?&#^()_+=\-{}\[\]:;"'<>,.?\/\\|`~]/.test(password)
    };
    
    // Count how many criteria are met
    Object.values(checks).forEach(check => {
        if (check) strength++;
    });
    
    return {
        score: strength,
        checks: checks,
        isValid: strength === 5
    };
}

function updateStrengthIndicator(strength) {
    const fill = document.getElementById('strength-bar-fill');
    const text = document.getElementById('strength-text');
    
    if (!fill || !text) return;
    
    const percentage = (strength.score / 5) * 100;
    fill.style.width = percentage + '%';
    
    // Update color and text based on strength
    if (strength.score === 0) {
        fill.className = 'strength-bar-fill';
        text.textContent = '';
    } else if (strength.score <= 2) {
        fill.className = 'strength-bar-fill weak';
        text.textContent = 'Weak';
    } else if (strength.score <= 3) {
        fill.className = 'strength-bar-fill fair';
        text.textContent = 'Fair';
    } else if (strength.score === 4) {
        fill.className = 'strength-bar-fill good';
        text.textContent = 'Good';
    } else {
        fill.className = 'strength-bar-fill strong';
        text.textContent = 'Strong';
    }
    
    // Show requirements status
    const requirements = [];
    if (!strength.checks.length) requirements.push('8+ characters');
    if (!strength.checks.lowercase) requirements.push('lowercase');
    if (!strength.checks.uppercase) requirements.push('uppercase');
    if (!strength.checks.number) requirements.push('number');
    if (!strength.checks.special) requirements.push('special character');
    
    if (requirements.length > 0) {
        text.textContent += ' - Missing: ' + requirements.join(', ');
    }
}
