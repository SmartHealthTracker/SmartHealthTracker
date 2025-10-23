<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function reply(Request $request)
    {
        $message = strtolower(trim($request->input('message')));
        $tips = [];

        // --- Initial options ---
        if ($message === 'fitness') {
            $tips = [
                ['title' => 'Upper Body', 'description' => 'Push-ups, Pull-ups, Dumbbell Press, Triceps Dips'],
                ['title' => 'Lower Body', 'description' => 'Squats, Lunges, Deadlifts, Calf Raises'],
                ['title' => 'Legs', 'description' => 'Leg Press, Hamstring Curls, Step-ups, Wall Sit']
            ];
        } elseif ($message === 'recipe') {
            $tips = [
                ['title' => 'Smoothie', 'description' => 'Banana + Spinach + Almond Milk + Honey, blend until smooth'],
                ['title' => 'Meals', 'description' => 'Grilled chicken, quinoa, steamed broccoli with olive oil dressing'],
                ['title' => 'Health Snacks', 'description' => 'Mixed nuts, Greek yogurt with berries, or hummus with veggies']
            ];
        }

        // --- Fitness categories ---
        elseif (in_array($message, ['upper', 'lower', 'legs'])) {
            $fitnessPlans = [
                'upper' => [
                    ['title' => 'Push-ups', 'description' => '3 sets of 15 reps'],
                    ['title' => 'Pull-ups', 'description' => '3 sets of 8 reps'],
                    ['title' => 'Dumbbell Press', 'description' => '3 sets of 12 reps'],
                    ['title' => 'Triceps Dips', 'description' => '3 sets of 12 reps']
                ],
                'lower' => [
                    ['title' => 'Squats', 'description' => '3 sets of 15 reps'],
                    ['title' => 'Lunges', 'description' => '3 sets of 12 reps per leg'],
                    ['title' => 'Deadlifts', 'description' => '3 sets of 10 reps'],
                    ['title' => 'Calf Raises', 'description' => '3 sets of 20 reps']
                ],
                'legs' => [
                    ['title' => 'Leg Press', 'description' => '3 sets of 12 reps'],
                    ['title' => 'Hamstring Curls', 'description' => '3 sets of 12 reps'],
                    ['title' => 'Step-ups', 'description' => '3 sets of 10 reps per leg'],
                    ['title' => 'Wall Sit', 'description' => '3 sets of 45 seconds']
                ]
            ];
            $tips = $fitnessPlans[$message];
        }

        // --- Recipe categories ---
        elseif (in_array($message, ['smoothie', 'meals', 'health snacks'])) {
            $recipes = [
                'smoothie' => [
                    ['title' => 'Banana Spinach Smoothie', 'description' => 'Banana + Spinach + 1 cup almond milk + 1 tsp honey, blend until smooth']
                ],
                'meals' => [
                    ['title' => 'Healthy Meal', 'description' => 'Grilled chicken, quinoa, steamed broccoli, olive oil dressing']
                ],
                'health snacks' => [
                    ['title' => 'Snack Options', 'description' => 'Mixed nuts, Greek yogurt with berries, or hummus with veggies']
                ]
            ];
            $tips = $recipes[$message];
        }

        // --- Default fallback ---
        if (empty($tips)) {
            $tips = [
                ['title' => 'Help', 'description' => "🤔 I didn't understand that. Type 'fitness' for plans or 'recipe' for healthy recipes."]
            ];
        }

        return response()->json(['reply' => $tips]);
    }
}
