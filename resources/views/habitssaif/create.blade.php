@extends('layout.master')

@section('content')
<div style="display:flex; justify-content:center; align-items:center; min-height:80vh; gap:30px; flex-wrap:wrap; flex-direction:row-reverse;">
    
    {{-- Image à droite --}}
    <div style="flex:1; min-width:200px; text-align:center;">
        <img src="{{ asset('images/addhabits.png') }}" alt="Photo" 
             style="width:300px; height:400px; border-radius:10px; object-fit:cover; box-shadow:0 3px 8px rgba(0,0,0,0.2);">
    </div>

    {{-- Formulaire à gauche --}}
    <div style="flex:1; min-width:280px; max-width:350px; background:#fff; padding:20px;margin-left:100px; border-radius:10px; box-shadow:0 3px 12px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; margin-bottom:15px; color:#4CAF50; font-size:18px;">Créer un Habit</h3>

        <form action="{{ route('habitssaif.store') }}" method="POST" 
              style="display:flex; flex-direction:column; gap:8px; font-size:13px;">
            @csrf

            <label style="font-weight:500;">Titre :</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                style="padding:6px; border-radius:5px; border:1px solid #ccc; font-size:13px;">

            <label style="font-weight:500;">Description :</label>
            <textarea name="description" rows="2"
                style="padding:6px; border-radius:5px; border:1px solid #ccc; font-size:13px;">{{ old('description') }}</textarea>

            <label style="font-weight:500;">Catégorie :</label>
            <input type="text" name="category" value="{{ old('category') }}" 
                style="padding:6px; border-radius:5px; border:1px solid #ccc; font-size:13px;">

            <label style="font-weight:500;">Objectif :</label>
            <input type="number" name="target_value" value="{{ old('target_value') }}" 
                style="padding:6px; border-radius:5px; border:1px solid #ccc; font-size:13px;">

            <label style="font-weight:500;">Unité :</label>
            <input type="text" name="unit" value="{{ old('unit') }}" 
                style="padding:6px; border-radius:5px; border:1px solid #ccc; font-size:13px;">

            <button type="submit" 
                style="padding:8px; background:#4CAF50; color:white; font-weight:600; border:none; border-radius:5px; cursor:pointer; margin-top:8px; font-size:13px;">
                Créer
            </button>
        </form>
    </div>

</div>
@endsection
