<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('sendRequest') }}">
        @csrf
        <div class="row justify-content-start">
            <div class="col-md-3 offset-md-4 mb-3">
                <h2 class="mb-3">Connect Your One</h2>
                <label for="username" class="form-label">Find Username</label>
                <input type="text" class="form-control mb-3" id="username" name="username" required>
                <button type="submit" class="btn btn-success">Send Request</button>
            </div>
        </div>
    </form>
</div>
@endsection
