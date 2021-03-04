@extends('layouts.app')

@section('content')
<div class="error-request">
    <h1>Unauthorized access</h1>
    @if($exception->getMessage())
        <h2>{{ $exception->getMessage() }}</h2>        
    @else
        <h2>You are not log in</h2>
    @endif
</div>

@endsection
<script>
    setTimeout(function() {
        window.history.back();
    }, 5000);
</script>