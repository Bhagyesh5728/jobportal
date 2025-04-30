@extends('layout.app')

@section('content')
    <div class="container py-5">
        <h1>My Resume</h1>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="row">
            <div class="col-md-6">
                @if ($resume)
                    <p>Current Resume:</p>
                    <a href="{{ $resume }}" target="_blank" class="btn btn-primary mb-3">View Current Resume (PDF)</a>
                @endif

                <form action="{{ route('resume.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="resume">
                            {{ $resume ? 'Change Resume (PDF only)' : 'Upload Resume (PDF only)' }}
                        </label>
                        <input type="file" name="resume" class="form-control" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">
                        {{ $resume ? 'Update Resume' : 'Upload Resume' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
