@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Branch Detail</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Branch</h4>
                </div>
                <div class="card-body">
                    <p><strong>Branch:</strong> {{ $branch->branch }}</p>
                </div>
                <div class="card-footer text-right">
                    <a class="btn btn-secondary" href="{{ route('branch.index') }}">Back</a>
                </div>
            </div>
        </div>
    </section>
@endsection
