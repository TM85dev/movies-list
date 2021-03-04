@extends('layouts.app')

@section('content')

<profile-page auth='{{ Auth::user() }}' />

@endsection