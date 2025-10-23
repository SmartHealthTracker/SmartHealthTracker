@extends('layout.master')

@section('content')
<h1>Modifier Habit Log</h1>

<form action="{{ route('habit-logs.update', $habitLog) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Habit:</label>
    <select name="habit_saif_id" required>
        @foreach ($habits as $habit)
            <option value="{{ $habit->id }}" {{ $habit->id == $habitLog->habit_id ? 'selected' : '' }}>
                {{ $habit->title }}
            </option>
        @endforeach
    </select><br>

    <label>Value:</label>
    <input type="number" name="value" value="{{ old('value', $habitLog->value) }}" required><br>

    <label>Date:</label>
    <input type="date" name="logged_at" value="{{ old('logged_at', $habitLog->logged_at) }}" required><br>

    <button type="submit">Mettre à jour</button>
</form>
@endsection
