<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (request()->routeIs('notebooks.show'))
                Notebooks
            @elseif (request()->routeIs('trash.index'))
                Trash
            @else
                Notes
            @endif
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-alert-success>{{ session('success') }}</x-alert-success>
            @if(request()->routeIs('notebooks.show'))
                <h1 class="font-bold text-4xl text-indigo-600">{{ $notebook->name }}</h1>
                <x-link-button href="{{ route('notebooks.addNote', ['notebook' => $notebook]) }}">
                    + New Note in this Notebook
                </x-link-button>
            @elseif(request()->routeIs('notes.index'))
                <x-link-button href="{{ route('notes.create') }}">
                    + New Note
                </x-link-button>
            @endif
            @forelse ($notes as $note)
              <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-bold text-2xl text-indigo-600">
                    <a 
                        @if(request()->routeIs('trash.index'))
                            href="{{ route('trash.show', $note) }}"
                        @else
                            href="{{ route('notes.show', $note) }}" 
                        @endif
                        class="hover:underline">
                        {{ $note->title }}
                    </a>
                </h2>
                @if(isset($note->notebook->name))
                    <p class="mt-4"><span class="px-2 py-1 bg-indigo-200 rounded border border-indigo-700 font-semibold text-sm">{{ $note->notebook->name }}</span></p>
                @endif
                <p class="mt-2">{{ Str::limit($note->text, 200, '...') }}</p>
                <span class="block mt-4 text-sm opacity-70">{{ $note->updated_at->diffForHumans() }}</span>
              </div>
            @empty
              @if (request()->routeIs('notebooks.show'))
                    <p>You have no notes in this Notebook yet.</p>
              @elseif (request()->routeIs('trash.index'))
                    <p>The trash is empty!</p>
              @else
                    <p>You have no notes yet.</p>
              @endif
            @endforelse
            {{ $notes->links() }}
        </div>
    </div>
</x-app-layout>
