@extends('layouts.app')

@section('content')

<item-page 
    data-movie="{{ $movie }}" 
    data-rate="{{ $movie->rate }}" 
    data-related-movies="{{ $related_movies }}" 
    auth="{{ $user }}"
    type="movie"
/>
@endsection