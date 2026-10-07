<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JobApplicationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Job $job)
    {
        Gate::authorize('apply', $job);  // $user is passed automatically
        return view('job_application.create', ['job' => $job]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Job $job, Request $request)
    {
        $job->jobApplications()->create([
            'user_id' => $request->user()->id, // if logged in, will retur the user. otherwise null
            ...$request->validate([
                'expected_salary' => 'required|min:1|max:1000000'
            ])
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job application submitted.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
