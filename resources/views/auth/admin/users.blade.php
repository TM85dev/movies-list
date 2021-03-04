@extends('layouts.app')

@section('content')

<admin-page-users 
    users-data="{{ $users }}" 
    auth="{{ auth()->user() }}"
/>

@endsection