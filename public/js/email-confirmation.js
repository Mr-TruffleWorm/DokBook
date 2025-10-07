let emailVerified = false;
let verificationCodeSent = false;

// Send verification code
async function sendVerificationCode() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const sendBtn = document.getElementById('sendCodeBtn');
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showAlert('Please enter a valid email address', 'error');
        emailInput.focus();
        return;
    }
    
    // Disable button and show loading
    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending...';
    
    try {
        const response = await fetch('/api/send-verification-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ email: email })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show verification section
            document.getElementById('verification-section').classList.remove('hidden');
            verificationCodeSent = true;
            
            // Disable email input
            emailInput.disabled = true;
            emailInput.classList.add('bg-gray-100');
            
            showAlert(data.message, 'success');
            
            // Start countdown timer (60 seconds)
            startCountdownTimer(sendBtn, 60);
            
            // Focus on verification code input
            document.getElementById('verification_code').focus();
        } else {
            showAlert(data.message || 'Failed to send verification code', 'error');
            sendBtn.disabled = false;
            sendBtn.textContent = 'Send Code';
        }
    } catch (error) {
        console.error('Error sending verification code:', error);
        showAlert('Network error. Please check your connection and try again.', 'error');
        sendBtn.disabled = false;
        sendBtn.textContent = 'Send Code';
    }
}

// Verify email code
async function verifyEmailCode() {
    const email = document.getElementById('email').value.trim();
    const code = document.getElementById('verification_code').value.trim();
    const messageDiv = document.getElementById('verification-message');
    
    // Validate code format
    if (code.length !== 6 || !/^\d{6}$/.test(code)) {
        messageDiv.textContent = 'Please enter a valid 6-digit code';
        messageDiv.className = 'text-xs mt-1 text-red-600';
        return;
    }
    
    messageDiv.textContent = 'Verifying...';
    messageDiv.className = 'text-xs mt-1 text-blue-600';
    
    try {
        const response = await fetch('/api/verify-email-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ 
                email: email,
                code: code 
            })
        });
        
        const data = await response.json();
        
        if (data.valid) {
            // Mark as verified
            emailVerified = true;
            
            // Show success state
            document.getElementById('verification-success').classList.remove('hidden');
            document.getElementById('verification-section').classList.add('hidden');
            
            // Clear message
            messageDiv.textContent = '';
            
            // Enable submit button
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
            submitBtn.classList.add('bg-green-500', 'hover:bg-green-600');
            
            showAlert('Email verified successfully!', 'success');
            
        } else {
            // Show error
            messageDiv.textContent = data.message || 'Invalid verification code';
            messageDiv.className = 'text-xs mt-1 text-red-600';
            
            // Clear and focus input
            document.getElementById('verification_code').value = '';
            document.getElementById('verification_code').focus();
        }
    } catch (error) {
        console.error('Error verifying code:', error);
        messageDiv.textContent = 'Verification failed. Please try again.';
        messageDiv.className = 'text-xs mt-1 text-red-600';
    }
}

// Resend code
function resendCode() {
    // Re-enable email input
    const emailInput = document.getElementById('email');
    emailInput.disabled = false;
    emailInput.classList.remove('bg-gray-100');
    
    // Reset send button
    const sendBtn = document.getElementById('sendCodeBtn');
    sendBtn.disabled = false;
    sendBtn.textContent = 'Send Code';
    sendBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
    sendBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
    
    // Hide verification section
    document.getElementById('verification-section').classList.add('hidden');
    document.getElementById('verification_code').value = '';
    document.getElementById('verification-message').textContent = '';
    
    verificationCodeSent = false;
    
    // Immediately send new code
    sendVerificationCode();
}

// Start countdown timer for resend
function startCountdownTimer(button, seconds) {
    let remaining = seconds;
    
    button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
    button.classList.add('bg-gray-400', 'cursor-not-allowed');
    
    const interval = setInterval(() => {
        remaining--;
        button.textContent = `Resend in ${remaining}s`;
        
        if (remaining <= 0) {
            clearInterval(interval);
            button.textContent = 'Send Code';
            button.disabled = false;
            button.classList.remove('bg-gray-400', 'cursor-not-allowed');
            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }
    }, 1000);
}

// Get CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ||
           document.querySelector('input[name="_token"]')?.value;
}

// Show alert message
function showAlert(message, type = 'info') {
    // You can customize this to use a toast notification library
    // For now, using native alert
    if (type === 'error') {
        alert('❌ ' + message);
    } else if (type === 'success') {
        alert('✅ ' + message);
    } else {
        alert('ℹ️ ' + message);
    }
}

// Auto-verify when 6 digits entered
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('verification_code');
    
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-verify when 6 digits entered
            if (this.value.length === 6) {
                verifyEmailCode();
            }
        });
        
        // Allow Enter key to verify
        codeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                verifyEmailCode();
            }
        });
    }
    
    // Allow Enter key on email to send code
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !verificationCodeSent) {
                e.preventDefault();
                sendVerificationCode();
            }
        });
        
        // Reset verification if email changes
        emailInput.addEventListener('input', function() {
            if (emailVerified) {
                // Reset verification state
                emailVerified = false;
                document.getElementById('verification-success').classList.add('hidden');
                document.getElementById('verification-section').classList.add('hidden');
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').classList.remove('bg-green-500', 'hover:bg-green-600');
                document.getElementById('submitBtn').classList.add('bg-gray-300', 'cursor-not-allowed');
            }
        });
    }
});

// Prevent form submission if email not verified
document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
    if (!emailVerified) {
        e.preventDefault();
        showAlert('Please verify your email address before booking the appointment.', 'error');
        
        // Navigate to step 3 if not already there
        const step3 = document.getElementById('step3');
        if (step3 && step3.classList.contains('hidden')) {
            goToStep(3);
        }
        
        // Focus on email input
        document.getElementById('email').focus();
        
        return false;
    }
});