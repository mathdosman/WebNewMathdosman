<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    public function index(){
        $data = Post::where('status','publish')->orderBy('id','desc')->paginate(10);
        return view('components.front.home-page', compact('data'));
    }
}
