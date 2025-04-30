@extends('layout.app')

@section('content')
    <div class="container py-5">
        <h1>Job List</h1>

        <div class="row">
          <livewire:job-post-list :isSaved="false" />
        </div>
    </div>
@endsection

