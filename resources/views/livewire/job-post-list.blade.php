<div>
    @if (session()->has('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
    <div class="mb-4">
        <input type="text" wire:model.live="search" class="form-control" placeholder="Search for jobs...">
    </div>
    <div class="row">
        @if ($jobs->isEmpty())
        <div class="col-12">
            <div class="alert alert-warning text-center">
                No jobs found.
            </div>
        </div>
    @else
        @foreach ($jobs as $job)
            <div class="col-md-4 mb-4">
                <div class="job-card p-3 border">
                    <h3>{{ $job->title }}</h3>
                    <p>{{ $job->description }}</p>
                    <p>Company: {{ $job->company }}</p>
                    <p>Location: {{ $job->location }}</p>
                    <p>Posted by: {{ $job->user->name ?? 'Unknown' }}</p>
                    <p>Posted on: {{ $job->created_at->diffForHumans() }}</p>
    
                    @if (Auth::check())
                        @if ($this->isJobSaved($job->id))
                            <button wire:click="unsaveJob({{ $job->id }})" class="btn btn-warning">Unsave Job</button>
                        @else
                            <button wire:click="saveJob({{ $job->id }})" class="btn btn-primary">Save Job</button>
                        @endif
                    @else
                        <button class="btn btn-primary" disabled>Save Job</button>
                    @endif
    
                    @if (Auth::check())
                        <button wire:click="applyForJob({{ $job->id }})" class="btn btn-success">Apply Job</button>
                    @else
                        <button class="btn btn-success" disabled>Apply Job</button>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
    
    </div>
</div>
