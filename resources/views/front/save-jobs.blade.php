@extends('layout.app')

@section('content')
    <div class="container py-5">
        <h1>Saved Jobs</h1>

        <div class="row">
            <livewire:job-post-list :isSaved="true" />

        </div>
    </div>
@endsection
