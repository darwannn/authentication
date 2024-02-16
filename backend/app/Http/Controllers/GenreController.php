<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Genre;
use App\Helpers\Response;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index()
    {
        try {
            $genres =  Genre::all();
            return Response::success(["genres" => $genres], null, 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function show($id)
    {
        try {
            $genre = Genre::find($id);
            if ($genre == null) {
                return Response::error('Resource not found', 404);
            }
            return Response::success(["genre" => $genre], null, 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function store(Request $request)
    {
        $inputs = $request->validate([
            'name' => ['required'],
        ]);
        try {

            $genre = Genre::create($inputs);
            return Response::success(["genre" => $genre], 'Genre created successfully', 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return Response::error('Resource not found', 404);
        }

        if (auth()->id() != $genre->user_id) {
            return Response::error('Unauthorized', 401);
        }

        $inputs = $request->validate([
            'name' => ['required'],
        ]);

        try {


            $genre->update($inputs);
            return Response::success(["genre" => $genre], 'Genre updated successfully', 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function destroy($id)
    {
        try {
            $genre = Genre::find($id);
            if (!$genre) {
                return Response::error('Resource not found', 404);
            }

            if (auth()->id() != $genre->user_id) {
                return Response::error('Unauthorized', 401);
            }

            $genre->delete();
            return Response::success(null, 'Genre deleted successfully', 200);
        } catch (Exception $e) {
            return response()->json($e, 500);
            error_log($e);
            return Response::error();
        }
    }
}
