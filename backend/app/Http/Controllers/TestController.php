<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public static function get_working_days()
    {
        $res = "";
        $holidays = [
            "2024-02-14 06:36:21",
        ];


        $submitted_at = Carbon::now();
        $deadline = $submitted_at->addWeekdays(5);

        foreach ($holidays as $holiday) {
            if (Carbon::parse($holiday)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                // dd(var_dump(Carbon::parse($holiday)->format('Y-m-d')));
                // dd(var_dump(Carbon::now()->format('Y-m-d')));
                // var_dump("equal");
                $deadline = $deadline->addWeekday(1);
            }
        }
        if ($deadline > Carbon::now()) {

            $res = "valid";
        } else {
            $res = "invalid";
        }

        return response()->json(
            [
                "res" => $res,
                "deadline" => $deadline,
                "skip" => Carbon::parse($holiday)->format('Y-m-d'),
                "today" => Carbon::now()->format('Y-m-d')
            ]
        );
    }
}
