function validatePhone() {
    const regex = /^0[67]\d{8}$/;
    if (!regex.test($(this).val())) {
        this.setCustomValidity("Phone must start with 06 or 07 and be 10 digits");
    } else {
        this.setCustomValidity("");
    }
}
$('#phone_no').on('input change', validatePhone);

// populate countries dropdowns
$.ajax({
    url: countriesUrl,
    method: 'GET',
    success: function(data) {
        data.forEach(function(country) {
            let option = `<option value="${country}">${country}</option>`;
            $('#selectOrigin').append(option);
            $('#selectHost').append(option);
        });
    },
    error: function() {
        $('#error-general').text('Failed to load countries. Please refresh the page.');
    }
});

// prevent selecting identical countries in real time
$('#selectOrigin, #selectHost').on('change', function() {
    const originVal = $('#selectOrigin').val();
    const hostVal = $('#selectHost').val();

    $('#selectHost option').prop('disabled', false);
    $('#selectOrigin option').prop('disabled', false);

    if (originVal) {
        $('#selectHost option[value="' + originVal + '"]').prop('disabled', true);
    }
    if (hostVal) {
        $('#selectOrigin option[value="' + hostVal + '"]').prop('disabled', true);
    }
});

let pendingPhone = '';

// step 1: submit registration form
$('#registerForm').on('submit', function(e) {
    e.preventDefault();
    $('.error-message').text('');

    if ($('#selectOrigin').val() === $('#selectHost').val()) {
        $('#error-host_country').text('Origin and host country cannot be the same');
        return;
    }

    const $submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = $submitBtn.text();
    $submitBtn.prop('disabled', true).text('Registering...');

    $.ajax({
        url: storeUrl,
        method: 'POST',
        data: {
            _token: csrfToken,
            name: $('#name').val(),
            phone_no: $('#phone_no').val(),
            date_of_birth: $('#date_of_birth').val(),
            country_of_origin: $('#selectOrigin').val(),
            host_country: $('#selectHost').val()
        },
        success: function(response) {
            if (response.status === 'otp_sent') {
                pendingPhone = $('#phone_no').val();
                $('#registerForm').hide();
                $('#otpSection').show();
                startResendCountdown();
            }
        },
        error: function(xhr) {
            $submitBtn.prop('disabled', false).text(originalBtnText);
            $('.error-message').text('');
            if (xhr.status === 422) {
                let errors = xhr.responseJSON;
                for (const field in errors) {
                    $('#error-' + field).text(errors[field][0]);
                }
            } else if (xhr.status === 500) {
                $('#error-general').text(xhr.responseJSON.message);
            } else {
                $('#error-general').text('An unexpected error occurred');
            }
        }
    });
});

// step 2: verify OTP
$('#verifyOtpBtn').on('click', function() {
    $('.error-message').text('');
    const otp = $('#otp').val().trim();

    if (!otp || otp.length !== 6) {
        $('#error-otp').text('Please enter the 6-digit code.');
        return;
    }

    const $btn = $(this);
    $btn.prop('disabled', true).text('Verifying...');

    $.ajax({
        url: otpUrl,
        method: 'POST',
        data: {
            _token: csrfToken,
            phone_no: pendingPhone,
            otp: otp
        },
        success: function(response) {
            if (response.status === 'registered') {
                window.location.href = dashboardUrl;
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).text('Verify');
            const status = xhr.responseJSON && xhr.responseJSON.status;
            if (status === 'invalid_otp') {
                $('#error-otp').text('Invalid code. Please check and try again.');
            } else if (status === 'expired') {
                $('#error-otp').text('Session expired. Please register again.');
                setTimeout(function() { window.location.reload(); }, 3000);
            } else {
                $('#error-otp').text('Verification failed. Please try again.');
            }
        }
    });
});

// resend OTP with 60-second cooldown
let resendTimer = null;

function startResendCountdown() {
    let seconds = 60;
    $('#resendOtpBtn').prop('disabled', true);
    clearInterval(resendTimer);
    resendTimer = setInterval(function() {
        seconds--;
        $('#resendCountdown').text('Resend available in ' + seconds + 's');
        if (seconds <= 0) {
            clearInterval(resendTimer);
            $('#resendOtpBtn').prop('disabled', false);
            $('#resendCountdown').text('');
        }
    }, 1000);
}

$('#resendOtpBtn').on('click', function() {
    const $btn = $(this);
    $btn.prop('disabled', true).text('Sending...');

    $.ajax({
        url: resendOtpUrl,
        method: 'POST',
        data: {
            _token: csrfToken,
            phone_no: pendingPhone
        },
        success: function() {
            $btn.text('Resend Code');
            $('#error-otp').text('');
            startResendCountdown();
        },
        error: function(xhr) {
            $btn.prop('disabled', false).text('Resend Code');
            if (xhr.status === 429) {
                $('#error-otp').text('Too many resend attempts. Please wait before trying again.');
            } else {
                $('#error-otp').text('Failed to resend code. Please try again.');
            }
        }
    });
});
