<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Todo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->is_admin()){
            $todos = new Todo;
        }else{
            $todos = Todo::where('user_id',Auth::user()->id);            
        }
        if(isset($_GET['search']))
            $todos = $todos->where('title','LIKE','%'.$_GET['search'].'%')->orWhere('todo','LIKE','%'.$_GET['search'].'%');
        if(isset($_GET['latest']))
            $todos = $todos->latest();
        if(isset($_GET['oldest']))
            $todos = $todos->oldest();

        $todos = $todos->paginate(10);
        return view('home',['todos'=>$todos]);
    }
}
