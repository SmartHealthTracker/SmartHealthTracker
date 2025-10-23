<x-mail::message>
# Hello {{ $participation->user->name }},

We’ve received your request to join the **{{ $participation->challenge->name }}** challenge.

<x-mail::panel>
**Challenge:** {{ $participation->challenge->name }}  
**Your Age:** {{ $participation->age }}  
**Your Weight:** {{ $participation->weight }}  
**Current Status:** 
@if ($participation->status === 'approved')
✅ Approved — You can now participate!
@elseif ($participation->status === 'pending')
⏳ Pending — Please wait for confirmation.
@else
❌ Rejected — Unfortunately, this challenge is full.
@endif
</x-mail::panel>

<x-mail::button :url="url('/challenges/' . $participation->challenge->id)">
View Challenge
</x-mail::button>

Thanks for participating,  
**{{ config('app.name') }}** Team
</x-mail::message>
