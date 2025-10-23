@extends('layout.app')

@section('title', 'Analyse Nutritionnelle')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">Analyse Nutritionnelle</h2>

    <form action="{{ route('nutrition.search') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="food" class="form-control" placeholder="Ex: 2 pommes, 1 yaourt nature" required>
            <button type="submit" class="btn btn-primary">Analyser</button>
        </div>
    </form>

    @isset($data)
        <h4 class="mt-5">Résultats pour "{{ $food }}" :</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Calories</th>
                    <th>Protéines (g)</th>
                    <th>Glucides (g)</th>
                    <th>Lipides (g)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['foods'] as $item)
                    <tr>
                        <td>{{ $item['food_name'] }}</td>
                        <td>{{ $item['nf_calories'] }}</td>
                        <td>{{ $item['nf_protein'] }}</td>
                        <td>{{ $item['nf_total_carbohydrate'] }}</td>
                        <td>{{ $item['nf_total_fat'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endisset
</div>
@endsection
