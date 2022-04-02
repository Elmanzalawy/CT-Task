<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Task::when($request->project_id, function($task) use ($request){
            $task->where("project_id", $request->project_id);
        })
        ->orderBy('priority')
        ->get();

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = Task::create([
            'name' => $request->name,
            'priority' => $request->priority ?? Task::max('priority') + 1,
            'project_id' => $request->project_id ?? null,
        ]);

        return response()->json($task->fresh());
    }

    public function swapTasks(Request $request){
        $task1 = Task::find($request->task1);
        $task2 = Task::find($request->task2);

        $tempPriority = $task1->priority;

        $task1->update([
            'priority' => $task2->priority,
        ]);

        $task2->update([
            'priority' => $tempPriority,
        ]);

        return response()->json([
            "task1" => $task1,
            "task2" => $task2
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $task->update([
            'name' => $request->name ?? $task->name,
            'priority' => $request->priority ?? $task->priority,
        ]);

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        return response()->json($task->delete());
    }
}
