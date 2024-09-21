<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Note
        </h2>
    </x-slot>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 max-w-2xl overflow-hidden shadow-sm sm:rounded-lg">
          <form action="{{ route('notes.store') }}" method="post">
            @csrf
            <x-text-input name="title" class="w-full" placeholder="Note Title" value="{{ @old('title') }}"></x-text-input>
            @error('title')
              <div class="text-sm mt-1 text-red-500">{{ $message }}</div>
            @enderror
            <x-textarea name="text" class="w-full mt-6" placeholder="Note Text" rows="8"  value="{{ @old('text') }}"></x-textarea>
            @error('text')
              <div class="text-sm mt-1 text-red-500">{{ $message }}</div>
            @enderror
            <select name="notebook_id" id="notebook_id" class="w-full mt-6 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              @if(request()->routeIs('notebooks.addNote'))
                disabled>
                <option value="{{ $notebook->id }}">{{ $notebook->name }}</option>
              @else
                >
                <option value="">-- Slelect Notebook --</option>
                @foreach ($notebooks as $notebook)
                  <option value="{{ $notebook->id }}">{{ $notebook->name }}</option>
                @endforeach
              @endif
            </select>
            @if(request()->routeIs('notebooks.addNote'))
              <input type="hidden" name="notebook_uuid" value="{{ $notebook->uuid }}">
              <input type="hidden" name="notebook_id" value="{{ $notebook->id }}">
            @endif
            <x-primary-button class="mt-6">Save Note</x-primary-button>
          </form>
        </div>
      </div>
    </div>
</x-app-layout>
