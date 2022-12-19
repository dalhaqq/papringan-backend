@extends('...blank')
@section('content')
<div class="auth-content">
    <div class="card">
        <div class="card-body text-center">
            <h6 class="mb-4 text-muted">Login to your account</h6>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email adress</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                    @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <button class="btn btn-primary shadow-2 mb-4">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection