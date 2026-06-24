function showError(id, msg) {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = msg;
    el.classList.remove('hidden');
}

function clearErrors() {
    document.querySelectorAll('.error-message').forEach(function(el) {
        el.textContent = '';
        el.classList.add('hidden');
    });
}

function validatePhone() {
    const regex = /^0[67]\d{8}$/;
    if (!regex.test($(this).val())) {
        this.setCustomValidity('Phone must start with 06 or 07 and be 10 digits');
    } else {
        this.setCustomValidity('');
    }
}
$('#phone_no').on('input change', validatePhone);

// populate countries dropdowns
$.ajax({
    url: countriesUrl,
    method: 'GET',
    success: function(data) {
        data.forEach(function(country) {
            const option = '<option value="' + country + '">' + country + '</option>';
            $('#selectOrigin').append(option);
            $('#selectHost').append(option);
        });
    },
    error: function() {
        showError('error-general', 'Failed to load countries. Please refresh the page.');
    }
});

// prevent selecting identical countries
$('#selectOrigin, #selectHost').on('change', function() {
    const originVal = $('#selectOrigin').val();
    const hostVal   = $('#selectHost').val();

    $('#selectHost option').prop('disabled', false);
    $('#selectOrigin option').prop('disabled', false);

    if (originVal) $('#selectHost option[value="' + originVal + '"]').prop('disabled', true);
    if (hostVal)   $('#selectOrigin option[value="' + hostVal + '"]').prop('disabled', true);
});

// submit registration form
$('#registerForm').on('submit', function(e) {
    e.preventDefault();
    clearErrors();

    if ($('#selectOrigin').val() === $('#selectHost').val() && $('#selectOrigin').val() !== '') {
        showError('error-host_country', 'Origin and host country cannot be the same.');
        return;
    }

    const $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).text('Creating account...');

    $.ajax({
        url: storeUrl,
        method: 'POST',
        data: {
            _token:               csrfToken,
            name:                 $('#name').val(),
            phone_no:             $('#phone_no').val(),
            date_of_birth:        $('#date_of_birth').val(),
            country_of_origin:    $('#selectOrigin').val(),
            host_country:         $('#selectHost').val(),
            password:             $('#password').val(),
            password_confirmation: $('#password_confirmation').val(),
        },
        success: function(response) {
            if (response.status === 'registered') {
                window.location.href = dashboardUrl;
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).text('Register');
            clearErrors();

            if (xhr.status === 422) {
                const errors = xhr.responseJSON || {};
                Object.entries(errors).forEach(function([field, messages]) {
                    showError('error-' + field, messages[0]);
                });
            } else {
                const msg = xhr.responseJSON && xhr.responseJSON.message;
                showError('error-general', msg || 'An unexpected error occurred. Please try again.');
            }
        }
    });
});
