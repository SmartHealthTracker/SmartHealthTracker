@extends('layout.master')

@section('content')
<h1>Ajouter un Habit Log</h1>

<form action="{{ route('habit-logs.store') }}" method="POST">
    @csrf
    <label>Habit:</label>
    <select name="habit_saif_id" required> {{-- ← Changed from habit_id to habit_saif_id --}}
        @foreach ($habits as $habit)
            <option value="{{ $habit->id }}">{{ $habit->title }}</option>
        @endforeach
    </select><br>

    <label>Value:</label>
    <input type="number" name="value" required><br>

    <label>Date:</label>
    <input type="date" name="logged_at" required><br>

    <button type="submit">Ajouter</button>
</form>
@endsection