<h1>Login</h1>

<form action="{{ route('login')}}" method="post">
    @csrf

    <input type="text" name="email" placeholder="Email">
    @error('email')
        <span  style="color: red">{{ $message }}</span>
    @enderror
    <br><br>
    <input type="password" name="password" placeholder="Password">
    @error('password')
        <span  style="color: red">{{ $message }}</span>
    @enderror
    <br><br>
    <button type="submit">Login</button>

</form>

@if(Session::has('error'))
    <p style="color: red">{{ Session::get('error') }}</p>

@endif

@if(Session::has('success'))
    <p style="color: red">{{ Session::get('success') }}</p>

@endif
