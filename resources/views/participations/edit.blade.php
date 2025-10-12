@extends('layout.master')

@section('title','Edit Participation')

@section('content')
<div class="card">
    <div class="card-header">Edit Participation</div>
    <div class="card-body">
        <form action="{{ route('participations.update', $participation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Challenge</label>
                <select name="challenge_id" class="form-control" required>
                    @foreach($challenges as $challenge)
                        <option value="{{ $challenge->id }}" {{ $challenge->id == $participation->challenge_id ? 'selected' : '' }}>
                            {{ $challenge->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>User</label>
                <select name="user_id" class="form-control" required>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $participation->user_id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ $participation->status=='pending'?'selected':'' }}>Pending</option>
                    <option value="approved" {{ $participation->status=='approved'?'selected':'' }}>Approved</option>
                    <option value="rejected" {{ $participation->status=='rejected'?'selected':'' }}>Rejected</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('participations.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
