<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Movie;
use Illuminate\Console\Command;
use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Notification;

class DailyMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        // echo "This is a test command";

        $movie = Movie::where('created_at', '<', Carbon::now())->first();
        if ($movie) {
            // $movie->delete();
            // $details = [
            //     "id" => $movie->id,
            //     "email" => 'darwinsanluis.ramos14@gmail.com',
            //     "name" => 'Darw In'
            // ];
            // Notification::send(auth()->user(), new EmailNotification($details));
            echo "email sent" . $movie;
        } else {
            echo "No movie found";
        }
    }
}
