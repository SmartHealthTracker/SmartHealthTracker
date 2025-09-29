@extends('layout.master')

@section('title','Add Challenge')

@section('content')
<div class="card">
    <div class="card-header">Add Challenge</div>
    <div class="card-body">
        <form action="{{ route('challenges.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
            <a href="{{ route('challenges.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
