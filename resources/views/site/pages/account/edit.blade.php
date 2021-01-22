@extends('site.app')
@section('title', 'edit account')
@section('content')
    <section class="section-pagetop bg-dark">
        <div class="container clearfix">
            <h2 class="title-page">Edit account</h2>
        </div>
    </section>
    <section class="section-content bg padding-y border-top" id="site">
        <div class="container">
@if( session('message'))
            <div class="alert alert-success">
                {{ session('message')}}
            </div>
        @endif
        @if( session('error'))
            <div class="alert alert-danger">
                {{ session('error')}}
            </div>
        @endif
<form method="POST" action="{{ route('account.update', Auth::user()->id) }}">
@csrf
@method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="name">last Name</label>
        <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" class="form-control">
    </div>
   
    <div>
                            <button type="submit" class="btn btn-primary">Update account</button>
                          </div>
</form>

</div>
        <!-- container .//  -->
    </section>
@stop