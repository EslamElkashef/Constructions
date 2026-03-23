<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_title.*' => 'required|string|max:255',
            'company_name.*' => 'nullable|string|max:255',
            'from_year.*' => 'nullable|integer',
            'to_year.*' => 'nullable|integer',
            'job_description.*' => 'nullable|string',
        ]);

        try {
            foreach ($request->job_title as $index => $title) {
                Experience::create([
                    'user_id' => auth()->id(),
                    'job_title' => $title,
                    'company_name' => $request->company_name[$index] ?? null,
                    'from_year' => $request->from_year[$index] ?? null,
                    'to_year' => $request->to_year[$index] ?? null,
                    'job_description' => $request->job_description[$index] ?? null,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Experience saved successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: '.$e->getMessage(),
            ], 500);
        }
    }
}
