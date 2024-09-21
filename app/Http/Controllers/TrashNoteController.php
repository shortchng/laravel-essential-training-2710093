<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashNoteController extends Controller
{
    /**
     * Display listing of notes in the trash
     */
    public function index()
    {
        $notes = Note::whereBelongsTo(Auth::user())->onlyTrashed()->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes', $notes);
    }

    /**
     * Display individual note from trash.
     */
    public function show(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            abort(403);
        }
        return view('notes.show', ['note' => $note]);
    }

    public function update(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            abort(403);
        }
        $note->restore();

        return to_route('notes.show', $note)
            ->with('success', 'Note Restored Successfully');
    }

    public function destroy(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            abort(403);
        }

        $note->forceDelete();
        return to_route('trash.index')
            ->with('success', 'Note deleted permanently');
    }
}
