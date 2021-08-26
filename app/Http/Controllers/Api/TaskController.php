<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index()
//     {
//         $tasks = Task::all();
//         return response()->json([
//             "success" => true,
//             "message" => "Task List",
//             "data" => $tasks
//         ]);
//     }
//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(StoreRequest $request)
//     {
//         $input = $request->all();

//         $task = Task::create($input);
//         return response()->json([
//             "success" => true,
//             "message" => "Task created successfully.",
//             "data" => $task
//         ]);
//     }
//     /**
//      * Display the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function show($id)
//     {
//         $task = Task::find($id);
//         if (is_null($task)) {
//             return $this->sendError('Task not found.');
//         }
//         return response()->json([
//             "success" => true,
//             "message" => "Task retrieved successfully.",
//             "data" => $task
//         ]);
//     }
//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function update(UpdateRequest $request, Task $task)
//     {
//         $input = $request->all();

//         $task->name = $input['name'];
//         $task->detail = $input['detail'];
//         $task->save();
//         return response()->json([
//             "success" => true,
//             "message" => "Task updated successfully.",
//             "data" => $task
//         ]);
//     }
//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(Task $task)
//     {
//         $task->delete();
//         return response()->json([
//             "success" => true,
//             "message" => "Task deleted successfully."
//         ]);
//     }
// }

{
    private $sucess_status = 200;

    // --------------- [ Create Task ] ------------------
    public function createTask(Request $request) {
        $user           =           Auth::user();

        $task_array         =       array(
            "name"        =>      $request->name,
            "status"            =>      $request->status,
            "user_id"           =>      $user->id
        );

        $task_id            =       $request->task_id;

        if($task_id != "") {
            $task_status    =       Task::where("id", $task_id)->update($task_array);

            if($task_status == 1) {
                return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Todo updated successfully", "data" => $task_array]);
            }

            else {
                return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Todo not updated"]);
            }

        }

        $task               =       Task::create($task_array);

        if(!is_null($task)) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $task]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! task not created."]);
        }
    }

    public function tasks_by_status_auth($status)
    {
        if ($status == 'success') {
            $tasks = api()->user()->tasks->whereIn('status', ['success', 'fail', 'acceptByUser', 'rejectByUser'])->sortByDesc('updated_at');
//            info(new tasksCollection($this->CollectionPaginate($tasks)));
        } else {
            $tasks = api()->user()->tasks->where('status', $status)->sortByDesc('updated_at');
        }
        return $this->apiResponse(new TasksCollection($this->CollectionPaginate($tasks)));
    }

    public function tasks_by_status($status)
    {
        if ($status == 'fail') {
            $tasks = Task::where('status', 'fail')->orderBy('created_at')->orWhere('status', 'acceptByUser')->paginate(20);
        } elseif ($status == 'success') {
            $tasks = Task::where('status', 'success')->orderBy('created_at')->orWhere('status', 'rejectByUser')->paginate(20);
        } else {
            $tasks = Task::where('status', $status)->orderBy('created_at')->paginate(20);
        }
        return $this->apiResponse(new TasksCollection($tasks));
    }

    // ---------------- [ Task Listing ] -----------------
    public function tasks() {
        $tasks          =           array();
        $user           =           Auth::user();
        $tasks          =           Task::where("user_id", $user->id)->get();
        if(count($tasks) > 0) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "count" => count($tasks), "data" => $tasks]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no todo found"]);
        }
    }

    // ------------------ [ Task Detail ] -------------------
    public function task($task_id) {
        if($task_id == 'undefined' || $task_id == "") {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
        }

        $task       =           Task::find($task_id);

        if(!is_null($task)) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $task]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no todo found"]);
        }
    }

    // ----------------- [ Delete Task ] -------------------
    public function deleteTask($task_id) {
        if($task_id == 'undefined' || $task_id == "") {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
        }

        $task       =           Task::find($task_id);

        if(!is_null($task)) {

            $delete_status  =   Task::where("id", $task_id)->delete();

            if($delete_status == 1) {

                return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Success! todo deleted"]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not deleted"]);
            }
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not found"]);
        }
    }
}
