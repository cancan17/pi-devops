<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ReminderHistory;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    //get all reminders
    public function index()
    {
        return response([
            'reminders' => Reminder::withTrashed()->orderBy('created_at', 'desc')->with('user:id,name')->where('user_id', auth()->user()->id)->get(),
        ], 200);
    }

    // show single reminder
    public function show($id)
    {
        return response([
            'reminder' => Reminder::where('id', $id)->get(),
        ], 200);
    }

    // crete a reminder
    public function store(Request $request)
    {
        // validate filds
        $attrs = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|integer',
            'color' => 'nullable|string',
            'alert' => 'nullable|date',
            'repeat' => 'nullable|string',
            'duration' => 'nullable|date',
        ]);

        $reminder = Reminder::create([
            'title' => $attrs['title'],
            'description' => $attrs['description'],
            'type' => $attrs['type'],
            'color' => $attrs['color'],
            'alert' => $attrs['alert'],
            'repeat' => $attrs['repeat'],
            'duration' => $attrs['duration'],
            'user_id' => auth()->user()->id,
        ]);

        return response([
            'message' => 'Reminder created.',
            'reminder' => $reminder,
        ], 200);
    }

    // update a reminder
    public function update(Request $request, $id)
    {
        $reminder = Reminder::find($id);

        if (! $reminder) {
            return response([
                'message' => 'Reminder not found.',
            ], 403);
        }

        if ($reminder->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.',
            ], 403);
        }

        // validate filds
        $attrs = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|integer',
            'alert' => 'nullable|string',
            'repeat' => 'nullable|string',
            'duration' => 'nullable|integer',
        ]);

        $reminder->update([
            'title' => $attrs['title'],
            'description' => $attrs['description'],
            'type' => $attrs['type'],
            'alert' => $attrs['alert'],
            'repeat' => $attrs['repeat'],
            'duration' => $attrs['duration'],
        ]);

        return response([
            'message' => 'Reminder updated.',
            'reminder' => $reminder,
        ], 200);
    }

    // delete reminder
    public function destroy($id)
    {
        $reminder = Reminder::find($id);

        if (! $reminder) {
            return response([
                'message' => 'Reminder not found.',
            ], 403);
        }

        if ($reminder->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.',
            ], 403);
        }

        $reminder->delete();

        return response([
            'message' => 'Reminder deleted.',
        ], 200);
    }

    public function deleteTrashedReminders()
    {
       $trashedReminders =  Reminder::onlyTrashed()->where('user_id', auth()->user()->id);

       if ($trashedReminders->count() == 0){
            return response([
                'message' => "There's no reminders to delete in history.",
            ], 403);
       }

        $trashedReminders->forceDelete();

        return response([
            'message' => 'History deleted.',
        ], 200);

    }
}