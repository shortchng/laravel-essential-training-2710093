<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Notebook;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotebookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notebooks = Notebook::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(10);
        return view('notebooks.index')->with('notebooks', $notebooks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notebooks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120'
        ]);

        $notebook = new Notebook([
            'user_id'   => Auth::id(),
            'uuid'      => Str::uuid(),
            'name'     => $request->name
        ]);
        $notebook->save();
        return to_route('notebooks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notebook $notebook)
    {
        if (!$notebook->user->is(Auth::user())) {
            abort(403);
        }

        $notes = Note::whereBelongsTo($notebook)->latest('updated_at')->paginate(5);

        return view('notes.index', ['notebook' => $notebook, 'notes' => $notes]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notebook $notebook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notebook $notebook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notebook $notebook)
    {
        //
    }

    /**
     * Add new Note within Notebook
     */
    public function addNote(Notebook $notebook)
    {
        if (!$notebook->user->is(Auth::user())) {
            abort(403);
        }
        //dd($notebook);
        return view('notes.create', ['notebook' => $notebook]);
    }
}
