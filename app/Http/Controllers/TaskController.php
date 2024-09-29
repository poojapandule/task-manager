<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
class TaskController extends Controller
{
    
        public function index()
        {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $tasks = $user->tasks;
            return response()->json($tasks);
        }
        
    

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,in_progress,completed',
        ]);
    
        // Create a new task
        $task = new Task();
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->status = $request->input('status'); // Save status
        $task->user_id = Auth::id();
        $task->save();
    
        return response()->json([
            'message' => 'Task created successfully!',
            'task' => $task,
        ], 201);
    }
    

    public function show(Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|string|in:pending,in_progress,completed',
    ]);

    $task->title = $request->title;
    $task->description = $request->description;
    $task->status = $request->status;
    $task->save();

    return response()->json($task);
}


    public function destroy(Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}