<h1>Register</h1>
<form action="{{route('registered')}}" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Enter Name">
    @error('name')
    <span style="color: red">{{$message}}</span>
    @enderror
    <br><br>

    <input type="email" name="email" placeholder="Enter email">
    @error('email')
    <span style="color: red">{{$message}}</span>
    @enderror
    <br><br>

    <input type="text" name="referral_code" placeholder="Enter Referral Code(Optional)">
    <br><br>
    <input type="password" name="password" placeholder="Enter password">
    @error('password')
    <span style="color: red">{{$message}}</span>
    @enderror
    <br><br>
    <input type="password" name="password_confirmation" placeholder="Enter confirm password">
    <br><br>
    <input type="submit" value="register">

</form>

@if (Session::has('success'))
    <p style="color: green">{{Session::get('success')}}</p>
@endif

@if (Session::has('error'))
    <p style="color: red">{{Session::get('error')}}</p>
@endif
