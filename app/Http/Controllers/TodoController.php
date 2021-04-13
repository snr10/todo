<?php

namespace App\Http\Controllers;

use App\Todo;
use Auth;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $data =  (array)$request->post();
         unset($data['_token']);
         $data['user_id'] = Auth::user()->id;
        $todo = Todo::create($data);

         return ['success'=>true,'id'=>$todo->id,'title'=>$todo->title,'date'=>$todo->date,'todo'=>$todo->todo];
    }

      /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $todo = Todo::where('id',$request->id)->first();
        return $todo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $todo = Todo::where('id',$request->id)->first();
        $data =  (array)$request->post();
        unset($data['_token']);
        $todo->update($data);
        return ['success'=>true,'date'=>$data['date']];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $todo = Todo::where('id',$request->id)->first();
        $todo->delete();
        return ['success'=>true];
    }
}
