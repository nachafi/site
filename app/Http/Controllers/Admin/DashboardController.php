<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LikeDislike;
use App\Models\Review;
use App\Models\Order;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $likes=LikeDislike::where('like','=','1')->get()->count();
        $dislikes=LikeDislike::where('dislike','=','1')->get()->count();
        $ratings=Review::where('rating','<=','5')->get()->sum('rating');
        $orders=Order::get()->count();

        $year = ['Jan','Feb','2017','2018','2019','2020'];

        $user = [];
        foreach ($year as $key => $value) {
            $user[] = User::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->count();
        }

    	return view('admin.dashboard.index')->with('likes',$likes)->with('dislikes',$dislikes)->with('ratings',$ratings)->with('orders',$orders)->with('year',json_encode($year,JSON_NUMERIC_CHECK))->with('user',json_encode($user,JSON_NUMERIC_CHECK));
    }
}
