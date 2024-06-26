<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index(){
        $todos = Todo::query()
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('completed');


        return view('welcome',[
            'todos' => $todos[0] ?? [],
            'completed' => $todos[1] ?? [],
        ]);

    }

    public function store(Request $request){
        $validate = $request->validate([
           'todo' => 'required'
        ]);

        $todo = Todo::query()->create([
            'user_id' => Auth::id(),
            'title' => $validate['todo']
        ]);

        return redirect('/');
    }

    public function edit(Todo $todo){
        return view('edit',[
            'todo' => $todo
        ]);
    }

    public function update(Request $request, Todo $todo){
        $validated = $request->validate([
            'todo' => 'required'
        ]);

        $todo->update([
            'title' => $validated['todo']
        ]);

        return redirect('/');
    }

    public function destroy(Todo $todo){
        $todo->delete();
        return redirect('/');
    }

    public function complete(Todo $todo){
        $todo->update([
            'completed' => !$todo->completed
        ]);
        return redirect('/');
    }
}
