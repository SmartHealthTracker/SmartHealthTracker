<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function reply(Request $request)
    {
        $message = strtolower(trim($request->input('message')));
        $reply = "🤔 I didn't understand that. You can type 'fitness' for fitness plans or 'recipe' for healthy recipes.";

        // --- Fitness start ---
        if ($message === 'fitness') {
            $reply = "💪 Great! Which type of fitness plan are you interested in?\n- Upper Body\n- Lower Body\n- Legs\nPlease type: 'upper', 'lower', or 'legs'.";
        }

        // --- Recipe start ---
        elseif ($message === 'recipe') {
            $reply = "🍳 Awesome! Which type of recipe do you want?\n- Smoothie\n- Meals\n- Health Snacks\nPlease type: 'smoothie', 'meals', or 'health snacks'.";
        }

        // --- Fitness categories ---
        elseif (in_array($message, ['upper', 'lower', 'legs'])) {
            $fitnessPlans = [
                'upper' => "🏋️ Upper Body Plan:\n- Push-ups: 3x15\n- Pull-ups: 3x8\n- Dumbbell Press: 3x12\n- Triceps Dips: 3x12",
                'lower' => "🏋️ Lower Body Plan:\n- Squats: 3x15\n- Lunges: 3x12 each leg\n- Deadlifts: 3x10\n- Calf Raises: 3x20",
                'legs'  => "🏋️ Legs Plan:\n- Leg Press: 3x12\n- Hamstring Curls: 3x12\n- Step-ups: 3x10 each leg\n- Wall Sit: 3x45s"
            ];
            $reply = $fitnessPlans[$message];
        }

        // --- Recipe categories ---
        elseif (in_array($message, ['smoothie', 'meals', 'health snacks'])) {
            $recipes = [
                'smoothie' => "🍹 Smoothie Recipe: Banana + Spinach + 1 cup almond milk + 1 tsp honey, blend until smooth.",
                'meals' => "🥗 Healthy Meal: Grilled chicken, quinoa, steamed broccoli, olive oil dressing.",
                'health snacks' => "🥜 Health Snack: Mixed nuts, Greek yogurt with berries, or hummus with veggies."
            ];
            $reply = $recipes[$message];
        }

        return response()->json(['reply' => $reply]);
    }
}
