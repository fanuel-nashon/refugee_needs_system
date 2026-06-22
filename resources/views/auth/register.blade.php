@extends('layouts.app')

@section('title', 'Refugee Registration')

@section('content')

    <form id="registerForm">
        @csrf
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Enter your name" required>
        <span class="error-message" id="error-name"></span>
        <br><br>
        <label for="phone_no">Phone Number</label>
        <input type="text" name="phone_no" id="phone_no" placeholder="Enter your phone number" required>
        <span class="error-message" id="error-phone_no"></span>
        <br><br>
        <label for="date_of_birth">Date of Birth</label>
        <input 
            type="date" 
            name="date_of_birth"    
            id="date_of_birth" 
            placeholder="Date of Birth" 
            min="1900-01-01" 
            max="{{ now()->subYears(18)->format('Y-m-d') }}"
        >
        <span class="error-message" id="error-date_of_birth"></span>
        <br><br>
        <label for="country_of_origin">Country of Origin</label>
        <select id="selectOrigin" name="country_of_origin">
            <option value="">-- Select Origin Country -- </option>
        </select>
        <span class="error-message" id="error-country_of_origin"></span>
        <br><br>
        <label for="host_country">Host Country</label>
        <select id="selectHost" name="host_country">
            <option value=""> -- Select Host Country -- </option>
        </select>
        <span class="error-message" id="error-host_country"></span>
        <br><br>
        <button type="submit">Register</button>
        <span class="error-message" id="error-general"></span>
    </form>

    @push('script')
        {{-- echo the routes and csrf token to send them to js as variables --}}
        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const countriesUrl="{{ route('register.countries') }}";
            const storeUrl="{{ route('register.store') }}";
            const dashboardUrl="{{ route('dashboard') }}";
            const otpUrl="{{ route('registration-otp') }}";
        </script>
        <script src="{{ asset('assets/js/register.js') }}"></script>       
    @endpush

@endsection