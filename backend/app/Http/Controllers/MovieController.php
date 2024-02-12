<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Movie;
use App\Helpers\Response;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // Public
    public function index()
    {
        try {
            $movies =  Movie::all();
            return Response::success(["movies" => $movies], null, 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    // Public
    public function show($id)
    {
        try {
            $movie = Movie::find($id);
            if ($movie == null) {
                return Response::error('Resource not found', 404);
            }
            return Response::success(["movie" => $movie], null, 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    // Public
    public function search($category)
    {
        try {
            $movie = Movie::where('category', 'like', '%' . $category . '%')->get();
            return Response::success(["movie" => $movie], null, 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }


    // Private
    public function store(Request $request)
    {
        $inputs = $request->validate([
            'title' => ['required'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,jpg,png'],
            'desctiption' => ['required'],
            'category' => ['required'],

        ]);
        try {
            if ($request->hasFile('thumbnail')) {
                $inputs['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }
            $inputs['user_id'] = auth()->id();
            $movie = Movie::create($inputs);
            return Response::success(["movie" => $movie], 'Movie created successfully', 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    // Private
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return Response::error('Resource not found', 404);
        }

        if (auth()->id() != $movie->user_id) {
            return Response::error('Unauthorized', 401);
        }

        $inputs = $request->validate([
            'title' => ['required'],
            'thumbnail' => ['image', 'mimes:jpeg,jpg,png'],

            'desctiption' => ['required'],
            'category' => ['required'],
        ]);

        try {
            if ($request->hasFile('thumbnail')) {
                $inputs['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $movie->update($inputs);
            return Response::success(["movie" => $movie], 'Movie updated successfully', 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    // Private
    public function destroy($id)
    {
        try {
            $movie = Movie::find($id);
            if (!$movie) {
                return Response::error('Resource not found', 404);
            }

            if (auth()->id() != $movie->user_id) {
                return Response::error('Unauthorized', 401);
            }

            $movie->delete();
            return Response::success(null, 'Movie deleted successfully', 200);
        } catch (Exception $e) {
            return response()->json($e, 500);
            error_log($e);
            return Response::error();
        }
    }
}
