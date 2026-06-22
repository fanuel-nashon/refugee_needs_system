//validate phone number

function validatePhone() {
    const regex=/^0[67]\d{8}$/;
    if(!regex.test($(this).val())) {
        this.setCustomValidity("Phone must start with 06 or 07 and be 10 digits");
    } else {
        this.setCustomValidity("");
    }
}
$('#phone_no').on('input change', validatePhone);

//populate countries
$.ajax({
    url: countriesUrl,
    method: 'GET',
    success: function(data) {
        data.forEach(function(country) {
            let option =`<option value="${country}">${country}</option>`;
            $('#selectOrigin').append(option);
            $('#selectHost').append(option);
        });
    }
});

//prevent selecting identical countries in real time
$('#selectOrigin, #selectHost').on('change', function() {
    const originVal=$('#selectOrigin').val();
    const hostVal=$('#selectHost').val();

    //reset disable states
    $('#selectHost option').prop('disabled', false);
    $('#selectOrigin option').prop('disabled', false);

    if (originVal) {
        $('#selectHost option[value="' + originVal + '"]').prop('disabled', true);
    }
    if (hostVal) {
        $('#selectOrigin option[value="' + hostVal + '"]').prop('disabled', true);
    }
});

$('#registerForm').on('submit', function(e) {
    e.preventDefault();
    $('.error-message').text(''); //clearing previous errors

    if($('#selectOrigin').val() === $('#selectHost').val()) {
        $('#error-host_country').text('Origin and host country cannot be the same');
        return;
    }

    const $submitBtn=$(this).find('button[type="submit"]');
    const originalBtnText=$submitBtn.text();
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
        success: function(response){
            window.location.href="{{ route('dashboard') }}"
        },
        error: function(xhr){
            $submitBtn.prop('disabled', false).text(originalBtnText);
            $('.error-message').text('');
            if(xhr.status===422){
                let errors=xhr.responseJSON;
                for(const field in errors){
                    $('#error-' + field).text(errors[field][0]);
                }
            } else if(xhr.status===500) {
                $('#error-general').text(xhr.responseJSON.message);
            } else {
                $('#error-general').text('An unexpected error occured');
            }
        }
    });  
});
