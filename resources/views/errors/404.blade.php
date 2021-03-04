
@extends('layouts.app')

@section('content')
<div class="error-request">
    <h1>Page not found</h1>
    @if($exception->getMessage())
        <h2>{{ $exception->getMessage() }}</h2>        
    @else
        <h2>This page does not exist</h2>
    @endif
</div>

@endsection
<script>
    setTimeout(function() {
        window.history.back();
    }, 5000);
</script>
