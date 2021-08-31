<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $sucess_status = 200;

    //Task Listing
    public function index()
    {
        $tasks = array();
        $user = Auth::user();
        $tasks = $user->tasks;
        $total = $user->tasks()->count();
        $success = round (($user->tasks()->where('status', 'success')->count() / $total) * 100,2);

        return response()->json(['data' => TaskResource::collection($tasks),'percentage' =>$success . '%']);
    }

    //Create And Store Task
    public function store(StoreRequest $request)
    {
        $inputs=$request->validated();
        $user = Auth::user();
       $task= $user->tasks()->create($inputs);
        return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $task]);
    }

    //Task Detail
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    //Update Task
    public function update(UpdateRequest $request, Task $task)
    {
        $input = $request->all();

        $task->update($request->validated());
        return response()->json([
            "success" => true,
            "message" => "Task updated successfully.",
            "data" => $task
        ]);
    }

    // Delete Task
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Success! todo deleted"]);
    }
}
