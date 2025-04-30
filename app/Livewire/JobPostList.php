<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\JobPost;
use Livewire\Component;
use App\Models\SavedJob;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class JobPostList extends Component
{
    public $search = '';
    public $isSaved = false;

    public function mount($isSaved = false)
    {
        $this->isSaved = $isSaved;
    }

    public function isJobSaved($jobId)
    {
        if (Auth::check()) {
            return SavedJob::where('job_post_id', $jobId)
                ->where('user_id', Auth::id())
                ->exists();
        }
        return false;
    }

    public function saveJob($jobId)
    {
        if (!Auth::check()) {
            session()->flash('message', 'Please login to save the job');
            return;
        }

        $savedJob = SavedJob::where('job_post_id', $jobId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$savedJob) {
            SavedJob::create([
                'job_post_id' => $jobId,
                'user_id' => Auth::id(),
            ]);
            session()->flash('message', 'Job saved successfully!');
        }
    }

    public function unsaveJob($jobId)
    {
        if (Auth::check()) {
            $savedJob = SavedJob::where('job_post_id', $jobId)
                ->where('user_id', Auth::id())
                ->first();

            if ($savedJob) {
                $savedJob->delete();
                session()->flash('message', 'Job unsaved successfully!');
            }
        }
    }

    public function applyForJob($jobId)
    {
        if (!Auth::check()) {
            session()->flash('message', 'Please login to apply for the job');
            return;
        }

        $user = Auth::user();
        $resume = $user->getFirstMedia(User::RESUME);

        if (!$resume) {
            session()->flash('message', 'Please upload your resume before applying for a job.');
            return;
        }

        $existingApplication = Application::where('job_post_id', $jobId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$existingApplication) {
            Application::create([
                'user_id' => Auth::id(),
                'job_post_id' => $jobId,
                'status' => Application::PENDING,
            ]);
            session()->flash('message', 'You have successfully applied for the job!');
        } else {
            session()->flash('message', 'You have already applied for this job.');
        }
    }

    public function render()
    {
        $data = JobPost::query();

        if ($this->isSaved && Auth::check()) {
            $savedJobIds = SavedJob::where('user_id', Auth::id())->pluck('job_post_id');
            $data->whereIn('id', $savedJobIds)->where('status', JobPost::ACTIVE);
        }

        if (!empty($this->search)) {
            $data->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('company', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%'); 
            });
        }

        $data->where('status', JobPost::ACTIVE); 
        
        $jobs = $data->latest()->get();

        return view('livewire.job-post-list', compact('jobs'));
    }
}
