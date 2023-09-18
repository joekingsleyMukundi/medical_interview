<?php

namespace App\Services;

use App\Models\Status;
use App\Models\Task;
use App\Models\UserTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $user = Auth::user();
        $allowedDepartments = ["Laboratory", "Radiology", "Optical"];
        if ($user && in_array($user->role, $allowedDepartments)) {
        $getTasks = Task::where('department', $user->role)
            ->orderBy("id", "DESC")
            ->get();
        } else {
            $getTasks = Task::orderBy("id", "DESC")->get();
        }
        $tasks = [];
        foreach ($getTasks as $task) {
            array_push($tasks, $this->structure($task));
        }

        return response($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        // Get default status
        //$statusId = Status::where("name", "Pending")->first()->id;

        $task = new Task;
        $task->name = $request->input("name");
        $task->description = $request->input("description");
        $task->due_date = $request->input("dueDate");
        $task->department = $request->input("department");
        $task->status = $request->input("status");
        //$task->status_id = $statusId;
        $task->save();

        return response("Task Created", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);

        return response($this->structure($task), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        $task = Task::find($id);

        if ($request->input("name")) {
            $task->name = $request->input("name");
        }

        if ($request->input("department")) {
            $task->department = $request->input("department");
        }

        if ($request->input("status")) {
            $task->status = $request->input("status");
        }

        if ($request->input("description")) {
            $task->description = $request->input("description");
        }

        if ($request->input("dueDate")) {
            $task->due_date = $request->input("dueDate");

            // Update user tasks as well
            $userTask = UserTask::where("task_id", $id)
                ->orderBy("id", "DESC")
                ->first();

            $userTask->due_date = $request->input("dueDate");
            $userTask->save();
        }



        $task->save();

        return response("Task Updated", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::find($id)->delete();

        return response("Task Deleted", 200);
    }

    /*
     * Handle Status */
    public function handleStatus($statusId, $id)
    {
        // Update user tasks as well
        $userTask = UserTask::where("task_id", $id)
            ->orderBy("id", "DESC")
            ->first();

        $userTask->status_id = $statusId;

        // If status has changed to Ongoing then add start date as today
        $statusName = Status::find($statusId)->name;

        if ($statusName == "Ongoing") {
            $userTask->start_time = Carbon::now();
        } elseif ($statusName == "Done") {
            $userTask->end_time = Carbon::now();
        } else {
            $userTask->start_time = null;
            $userTask->end_time = null;
        }

        $userTask->save();
    }

    /*
     * Structure the data */
    public function structure($task)
    {
        return [
            "id" => $task->id,
            "name" => $task->name,
            "description" => $task->description,
            "dueDate" => $task->due_date,
            "department" => $task->department,
            "status" => $task->status,
            "assigneeId" => $task->assigneeId(),
            "assigneeName" => $task->assigneeName(),
            "updatedAt" => $task->updated_at,
            "createdAt" => $task->created_at,
        ];
    }
}
