<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index()
    {
        return view('front.index');
    }

    public function saveJob(Request $request)
    {
        $jobId = $request->job_id;
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please log in first.']);
        }

        $alreadySaved = SavedJob::where('job_id', $jobId)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadySaved) {
            return response()->json(['success' => false, 'message' => 'You already saved this job.']);
        }

        SavedJob::create([
            'job_id' => $jobId,
            'user_id' => $userId,
        ]);

        return response()->json(['success' => true, 'message' => 'Job saved successfully.']);
    }

    public function unsaveJob(Request $request)
    {
        $jobId = $request->job_id;
        $userId = auth()->id();

        SavedJob::where('job_id', $jobId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Unsaved successfully.']);
    }

    public function savedJob()
    {
        $jobs = auth()->user()->savedJobs;
        
        return view('front.save-jobs', compact('jobs'));
    }

    public function myResume()
    {
        $resume = auth()->user()->getFirstMediaUrl(User::RESUME);

        return view('front.my-resume', compact('resume'));
    }

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => 'required|mimes:pdf',
        ]);
    
        $user = Auth::user();
    
        $user->clearMediaCollection(User::RESUME);
    
        $user->addMediaFromRequest('resume')->toMediaCollection(User::RESUME);
    
        return redirect()->back()->with('message', 'Resume uploaded successfully.');
    }
}
