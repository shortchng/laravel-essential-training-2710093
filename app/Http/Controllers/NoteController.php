<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Notebook;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes', $notes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $notebooks = Notebook::whereBelongsTo(Auth::user())->get();

        return view('notes.create')->with('notebooks', $notebooks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        $note = new Note([
            // Could also use Auth::user()->notes()->create()([ ... to leave out user_id and take advantage of eloquent relationships
            'user_id'       => Auth::id(),
            'uuid'          => Str::uuid(),
            'title'         => $request->title,
            'text'          => $request->text,
            'notebook_id'   => $request->notebook_id
        ]);
        $note->save();

        if (isset($request->notebook_uuid)) {
            return to_route('notebooks.show', $request->notebook_uuid);
        } else {
            return to_route('notes.show', $note)
                ->with('success', 'New Note Created!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            abort(403);
        }
        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            abort(403);
        }
        $notebooks = Notebook::whereBelongsTo(Auth::user())->get();
        return view('notes.edit', ['note' => $note, 'notebooks' =>  $notebooks]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        if (!$note->user->is(Auth::user())) {
            abort(403);
        }

        $note->update([
            'title'         => $request->title,
            'text'          => $request->text,
            'notebook_id'   => $request->notebook_id
        ]);

        return to_route('notes.show', $note)
            ->with('success', 'Changes Saved!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            abort(403);
        }
        $note->delete();
        return to_route('notes.index')
            ->with('success', 'Note moved to trash!');
    }
}
