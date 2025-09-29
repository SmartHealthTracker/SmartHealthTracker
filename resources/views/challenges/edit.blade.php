@extends('layout.master')

@section('title','Edit Challenge')

@section('content')
<div class="card">
    <div class="card-header">Edit Challenge</div>
    <div class="card-body">
        <form action="{{ route('challenges.update', $challenge->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ $challenge->name }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ $challenge->description }}</textarea>
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" value="{{ $challenge->start_date }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" value="{{ $challenge->end_date }}" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('challenges.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
