<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function viewAnyEmployer(User $user): bool {
        return true;

        // no additional checks needed, as you need to be:
        // 1. authenticated
        // 2. if you're not an employer, you're going to be redirected by the Middleware
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Job $job): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->employer !== null;  // check if user has an amployer instance
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Job $job): bool|Response
    {
        if($user->id !== $job->employer->user_id) return false;  // check is user the owner

        if ($job->jobApplications()->count() > 0) {  // method so that not all jobApplications are loaded, and it's just a query => efficiency
            // check if there are any applicants

            return Response::deny('You cannot edit a job with applications.'); // return a custom 403 message
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Job $job): bool
    {
        return $user_id === $job->employer->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Job $job): bool
    {
        return $user_id === $job->employer->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Job $job): bool
    {
        return $user_id === $job->employer->user_id;
    }

    // we could also create a JobApplication policy, but it involves around a Job
    public function apply(User $user, Job $job): bool {
        return !$job->hasUserApplied($user);
    }
}
