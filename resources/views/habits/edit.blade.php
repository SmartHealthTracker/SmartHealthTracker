@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Modifier Habit</h4>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('habits.update', $habit->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $habit->name) }}" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $habit->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Mettre à jour</button>
            <a href="{{ route('habits.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
