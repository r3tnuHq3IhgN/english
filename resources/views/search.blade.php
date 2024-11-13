@extends('layouts.app')
@section('content')
    <search :user_id="{{ Auth::user()->id }}"/>
@endsection