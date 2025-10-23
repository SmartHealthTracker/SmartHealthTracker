<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NutritionController extends Controller
{
    public function getNutrition(Request $request)
    {
        $request->validate([
            'ingredient' => 'required|string'
        ]);

        $ingredient = $request->input('ingredient');

        try {
            $response = Http::get("https://world.openfoodfacts.org/cgi/search.pl", [
                'search_terms' => $ingredient,
                'search_simple' => 1,
                'action' => 'process',
                'json' => 1
            ]);

            $data = $response->json();

            if (!isset($data['products'][0])) {
                return response()->json(['error' => 'Aucun produit trouvé']);
            }

            $product = $data['products'][0];
            $nutriments = $product['nutriments'] ?? [];

            return response()->json([
                'calories' => $nutriments['energy-kcal_100g'] ?? 0,
                'protein' => $nutriments['proteins_100g'] ?? 0,
                'carbs' => $nutriments['carbohydrates_100g'] ?? 0,
                'fat' => $nutriments['fat_100g'] ?? 0,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }
}
