@extends('layouts.app')
@section('content')
    <game :user_id="{{ Auth::user()->id }}"/>
@endsection