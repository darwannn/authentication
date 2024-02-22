<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Movie;
use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Notifications\EmailNotification;
use App\Notifications\MovieNotification;
use Illuminate\Support\Facades\Notification;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page_size = $request->paginate ?? 20;
            $movies = Movie::query()->paginate($page_size);
            return Response::success(["movies" => $movies], null, 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }


    // public function index()
    // {

    //     try {
    //         $movies =  Movie::all();
    //         return Response::success(["movies" => $movies], null, 200);
    //     } catch (Exception $e) {
    //         error_log($e);
    //         return Response::error();
    //     }
    // }

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

    public function store(Request $request)
    {
        // $adminUsers = User::where('role', 'admin')->get();
        //without auth()->user()
        //$this->authorize('admin-only');
        /**
         * Sends a movie notification to the authenticated user.
         *
         * @param string $title The title of the movie notification.
         * @param string $message The message of the movie notification.
         * @return void
         */

        //if (Gate::allows('admin-only', auth()->user())) {
        $inputs = $request->validate([
            'title' => ['required'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,jpg,png'],
            'desctiption' => ['required']
        ]);



        if ($request->hasFile('thumbnail')) {
            $inputs['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $inputs['user_id'] = auth()->id();
        $movie = Movie::create($inputs);
        $details = [
            "id" => $movie->id,
            "email" => 'darwinsanluis.ramos14@gmail.com',
            "name" => 'Darw In'
        ];
        Notification::send(auth()->user(), new MovieNotification("title", "message", $movie->id));
        Notification::send(auth()->user(), new EmailNotification($details));

        return Response::success(["movie" => $movie], 'Movie created successfully', 200);

        //}
        return Response::error('Unauthorized', 403);
    }

    public function search($title)
    {
        try {

            $movies = Movie::where('title', 'like', '%' . $title . '%')->get();
            return Response::success(["movies" => $movies], null, 200);
        } catch (Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function update(Request $request, $id)
    {
        echo "updates" . $request->title;
        $movie = Movie::find($id);
        if (!$movie) {
            return Response::error('Resource not found', 404);
        }
        try {
            $this->authorize('update', [$movie]);
            // if (auth()->id() != $movie->user_id) {
            //     return Response::error('Unauthorized', 401);
            // }

            $inputs = $request->validate([
                'title' => ['required'],
                'thumbnail' => ['image', 'mimes:jpeg,jpg,png'],

                'desctiption' => ['required'],
            ]);


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
