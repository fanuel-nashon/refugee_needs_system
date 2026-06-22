@extends('layouts.app')

@section('title', 'Refugee Needs System - Login')

@section('content')
  
    <form id="loginForm">
        @csrf
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <button type="submit">Login</button>

        <p id="responseTxt" style="display: none;"></p>

        <p>Do not have an account?, Click <a href="{{ route('register.create') }}">here</a> to register
   
    </form>

    <script>
        // function to validate login by taking credentials and sending request to the controller and displaying response using AJAX
        function validateLogin(e){
            e.preventDefault();
            let $msg = $('#responseTxt');

            $.ajax({
                url: "{{ route('login') }}",
                method: 'POST',
                data: {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $msg.css('display', 'block')
                    $msg.text(response.message);
                    window.location.href="{{ route('dashboard') }}";
                },
                error: function (xhr) {
                    $msg.css('display', 'block')

                    //error handling if no connection at all
                    if(xhr.status===0) {
                        $msg.text('Connection error: Unable to reach server');
                    } elseif (xhr.status === 401){
                        $msg.text('Wrong credentials. Please try again.');
                    } else {
                        let res = xhr.responseJSON;
                        $msg.text(res && res.message ? res.message : 'Unexpected message');
                    }
                }
            });
        }

        //attaching the function to the form submit
        $(document).ready(function( {
            $('#loginForm').on('submit', validateLogin);
        }))
    </script>

@endsection