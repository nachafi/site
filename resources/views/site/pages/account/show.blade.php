@extends('site.app')
@section('title', ' account')
@section('content')
    <section class="section-pagetop bg-dark">
        <div class="container clearfix">
            <h2 class="title-page"> Account</h2>
        </div>
    </section>
    <section class="section-content bg padding-y border-top" id="site">
        <div class="container">

<div class="flex justify-between items-center mb-6">
            <div style="max-width: 270px">
                <h2 class="font-bold text-2xl mb-0">{{ Auth::user()->full_name }}</h2>
                <p class="text-sm">Joined {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</p>
            </div>
            </div>
        <!-- container .//  -->
    </section>
@stop