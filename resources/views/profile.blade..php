@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-5 h2">

            Profile

        </div>
    </div>
    <div class="col-9">
        {{ $name }}
    </div>
</div>
@endsection
