<?php
namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChallengeCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $search = $request->input('search');
        
        // Build query with filters
        $query = Challenge::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        // Filter by month if specified
        if ($month && $year) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q) use ($startDate, $endDate) {
                      $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                  });
            });
        }
        
        $challenges = $query->orderBy('start_date')->get();
        
        // Generate calendar data
        $calendarData = $this->generateCalendarData($month, $year, $challenges);
        
        return view('challenges.calendar', compact(
            'calendarData', 
            'challenges',
            'month',
            'year',
            'search'
        ));
    }
    
    private function generateCalendarData($month, $year, $challenges)
    {
        $date = Carbon::create($year, $month, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        // Start from the first day of the week (Sunday)
        $startDate = $startOfMonth->copy()->startOfWeek();
        $endDate = $endOfMonth->copy()->endOfWeek();
        
        $calendar = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $week = [];
            
            for ($i = 0; $i < 7; $i++) {
                $dateChallenges = $challenges->filter(function($challenge) use ($currentDate) {
                    return $challenge->isActiveOnDate($currentDate);
                });
                
                $week[] = [
                    'date' => $currentDate->copy(),
                    'isCurrentMonth' => $currentDate->month == $month,
                    'isToday' => $currentDate->isToday(),
                    'challenges' => $dateChallenges
                ];
                
                $currentDate->addDay();
            }
            
            $calendar[] = $week;
        }
        
        return $calendar;
    }
}