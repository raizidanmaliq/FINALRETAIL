@extends('layouts.common.app')
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4 text-center">{{ $informationPage->title }}</h1>
            <div class="card p-4">
                {!! $informationPage->content !!}
            </div>
        </div>
    </div>
</div>
@endsection

