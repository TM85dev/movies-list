<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Movie;
use App\Models\Serie;

class Api extends Controller
{
    public function index($genre, $year, $sort, Movie $movie, Serie $serie) {
        $movies = $movie->all();
        $series = $serie->all();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
            $series = $serie->yearFilter($series, $year);
        }
        if($sort === 'asort-title') {
            $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
        } elseif($sort === 'dsort-title') {
            $all = $movies->concat($series)->sortByDesc('title')->values()->chunk(10);
        } elseif($sort === 'asort-year') {
            $all = $movies->concat($series)->sortBy('year')->values()->chunk(10);
        } elseif($sort === 'dsort-year') {
            $all = $movies->concat($series)->sortByDesc('year')->values()->chunk(10);
        }
        return response()->json($all);
    }

    public function movies($genre, $year, $sort, Movie $movie) {
        $movies = $movie->all();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
        }
        if($sort === 'asort-title') {
            $movies = $movies->sortBy('title')->values()->chunk(10);
        } elseif($sort === 'dsort-title') {
            $movies = $movies->sortByDesc('title')->values()->chunk(10);
        } elseif($sort === 'asort-year') {
            $movies = $movies->sortBy('year')->values()->chunk(10);
        } elseif($sort === 'dsort-year') {
            $movies = $movies->sortByDesc('year')->values()->chunk(10);
        }
        return response()->json($movies);
    }

    public function series($genre, $year, $sort, Serie $serie) {
        $series = $serie->all();
        if($genre !== 'all') {
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $series = $serie->yearFilter($series, $year);
        }
        if($sort === 'asort-title') {
            $series = $series->sortBy('title')->values()->chunk(10);
        } elseif($sort === 'dsort-title') {
            $series = $series->sortByDesc('title')->values()->chunk(10);
        } elseif($sort === 'asort-year') {
            $series = $series->sortBy('year')->values()->chunk(10);
        } elseif($sort === 'dsort-year') {
            $series = $series->sortByDesc('year')->values()->chunk(10);
        }
        return response()->json($series);
    }

    public function searchAll($genre, $year, $sort, $input, Movie $movie, Serie $serie) {
        $movies = $movie->where('title', 'LIKE', '%'.$input.'%')->get();
        $series = $serie->where('title', 'LIKE', '%'.$input.'%')->get();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
            $series = $serie->yearFilter($series, $year);
        }
        if($sort === 'asort-title') {
            $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
        } elseif($sort === 'dsort-title') {
            $all = $movies->concat($series)->sortByDesc('title')->values()->chunk(10);
        } elseif($sort === 'asort-year') {
            $all = $movies->concat($series)->sortBy('year')->values()->chunk(10);
        } elseif($sort === 'dsort-year') {
            $all = $movies->concat($series)->sortByDesc('year')->values()->chunk(10);
        }
        return response()->json($all);
    }
    public function searchMovies($genre, $year, $sort, $input, Movie $movie) {
        $movies = $movie->where('title', 'LIKE', '%'.$input.'%')->get();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
        }
        if($sort === 'asort-title') {
            $movies = $movies->sortBy('title')->values()->chunk(10);
        } elseif($sort === 'dsort-title') {
            $movies = $movies->sortByDesc('title')->values()->chunk(10);
        } elseif($sort === 'asort-year') {
            $movies = $movies->sortBy('year')->values()->chunk(10);
        } elseif($sort === 'dsort-year') {
            $movies = $movies->sortByDesc('year')->values()->chunk(10);
        }
        return response()->json($movies);
    }
    public function searchSeries($genre, $year, $sort, $input, Serie $serie) {
        $series = $serie->where('title', 'LIKE', '%'.$input.'%')->get();
        if($genre !== 'all') {
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $series = $serie->yearFilter($series, $year);
        }
        if($sort === 'asort-title') {
            $series = $series->sortBy('title')->values()->chunk(10);
        } elseif($sort === 'dsort-title') {
            $series = $series->sortByDesc('title')->values()->chunk(10);
        } elseif($sort === 'asort-year') {
            $series = $series->sortBy('year')->values()->chunk(10);
        } elseif($sort === 'dsort-year') {
            $series = $series->sortByDesc('year')->values()->chunk(10);
        }
        return response()->json($series);
    }
}































// public function index($genre, $year, $sort) {
//     $movies = Movie::all();
//     $series = Serie::all();
//     if($genre !== 'all') {
//         $movies = $movies->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//         $series = $series->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//     } 
//     if($year !== 'all') {
//         $years = explode(',', $year);
//         $filter_movies = collect([]);
//         $filter_series = collect([]);
//         foreach ($years as $current) {
//             $filter_movies = $filter_movies->concat($movies->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//             $filter_series = $filter_series->concat($series->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//         }
//         $movies = $filter_movies;
//         $series = $filter_series;
//     }
//     if($sort === 'asort-title') {
//         $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
//     } elseif($sort === 'dsort-title') {
//         $all = $movies->concat($series)->sortByDesc('title')->values()->chunk(10);
//     } elseif($sort === 'asort-year') {
//         $all = $movies->concat($series)->sortBy('year')->values()->chunk(10);
//     } elseif($sort === 'dsort-year') {
//         $all = $movies->concat($series)->sortByDesc('year')->values()->chunk(10);
//     }
//     return response()->json($all);
// }



// public function movies($genre, $year, $sort) {
//     $movies = Movie::all();
//     if($genre !== 'all') {
//         $movies = $movies->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//     } 
//     if($year !== 'all') {
//         $years = explode(',', $year);
//         $filter_movies = collect([]);
//         foreach ($years as $current) {
//             $filter_movies = $filter_movies->concat($movies->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//         }
//         $movies = $filter_movies;
//     }
//     if($sort === 'asort-title') {
//         $movies = $movies->sortBy('title')->values()->chunk(10);
//     } elseif($sort === 'dsort-title') {
//         $movies = $movies->sortByDesc('title')->values()->chunk(10);
//     } elseif($sort === 'asort-year') {
//         $movies = $movies->sortBy('year')->values()->chunk(10);
//     } elseif($sort === 'dsort-year') {
//         $movies = $movies->sortByDesc('year')->values()->chunk(10);
//     }
//     return response()->json($movies);
// }

// public function series($genre, $year, $sort) {
//     $series = Serie::all();
//     if($genre !== 'all') {
//         $series = $series->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//     } 
//     if($year !== 'all') {
//         $years = explode(',', $year);
//         $filter_series = collect([]);
//         foreach ($years as $current) {
//             $filter_series = $filter_series->concat($series->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//         }
//         $series = $filter_series;
//     }
//     if($sort === 'asort-title') {
//         $series = $series->sortBy('title')->values()->chunk(10);
//     } elseif($sort === 'dsort-title') {
//         $series = $series->sortByDesc('title')->values()->chunk(10);
//     } elseif($sort === 'asort-year') {
//         $series = $series->sortBy('year')->values()->chunk(10);
//     } elseif($sort === 'dsort-year') {
//         $series = $series->sortByDesc('year')->values()->chunk(10);
//     }
//     return response()->json($series);
// }

// public function searchAll($genre, $year, $sort, $input) {
//     $movies = Movie::where('title', 'LIKE', '%'.$input.'%')->get();
//     $series = Serie::where('title', 'LIKE', '%'.$input.'%')->get();
//     if($genre !== 'all') {
//         $movies = $movies->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//         $series = $series->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//     } 
//     if($year !== 'all') {
//         $years = explode(',', $year);
//         $filter_movies = collect([]);
//         $filter_series = collect([]);
//         foreach ($years as $current) {
//             $filter_movies = $filter_movies->concat($movies->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//             $filter_series = $filter_series->concat($series->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//         }
//         $movies = $filter_movies;
//         $series = $filter_series;
//     }
//     if($sort === 'asort-title') {
//         $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
//     } elseif($sort === 'dsort-title') {
//         $all = $movies->concat($series)->sortByDesc('title')->values()->chunk(10);
//     } elseif($sort === 'asort-year') {
//         $all = $movies->concat($series)->sortBy('year')->values()->chunk(10);
//     } elseif($sort === 'dsort-year') {
//         $all = $movies->concat($series)->sortByDesc('year')->values()->chunk(10);
//     }
//     return response()->json($movies);
// }
// public function searchMovies($genre, $year, $sort, $input) {
//     $movies = Movie::where('title', 'LIKE', '%'.$input.'%')->get();
//     if($genre !== 'all') {
//         $movies = $movies->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//     } 
//     if($year !== 'all') {
//         $years = explode(',', $year);
//         $filter_movies = collect([]);
//         foreach ($years as $current) {
//             $filter_movies = $filter_movies->concat($movies->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//         }
//         $movies = $filter_movies;
//     }
//     if($sort === 'asort-title') {
//         $movies = $movies->sortBy('title')->values()->chunk(10);
//     } elseif($sort === 'dsort-title') {
//         $movies = $movies->sortByDesc('title')->values()->chunk(10);
//     } elseif($sort === 'asort-year') {
//         $movies = $movies->sortBy('year')->values()->chunk(10);
//     } elseif($sort === 'dsort-year') {
//         $movies = $movies->sortByDesc('year')->values()->chunk(10);
//     }
//     return response()->json($movies);
// }
// public function searchSeries($genre, $year, $sort, $input) {
//     $series = Serie::where('title', 'LIKE', '%'.$input.'%')->get();
//     if($genre !== 'all') {
//         $series = $series->filter(function($value, $key) use($genre) {
//             return strtolower($value['genre']) === $genre;
//         });
//     } 
//     if($year !== 'all') {
//         $years = explode(',', $year);
//         $filter_series = collect([]);
//         foreach ($years as $current) {
//             $filter_series = $filter_series->concat($series->filter(function($value, $key) use($current) {
//                 return strtolower($value['year']) === $current;
//             }));
//         }
//         $series = $filter_series;
//     }
//     if($sort === 'asort-title') {
//         $series = $series->sortBy('title')->values()->chunk(10);
//     } elseif($sort === 'dsort-title') {
//         $series = $series->sortByDesc('title')->values()->chunk(10);
//     } elseif($sort === 'asort-year') {
//         $series = $series->sortBy('year')->values()->chunk(10);
//     } elseif($sort === 'dsort-year') {
//         $series = $series->sortByDesc('year')->values()->chunk(10);
//     }
//     return response()->json($movies);
// }

