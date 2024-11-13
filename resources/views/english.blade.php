@extends('layouts.app')
@section('content')
<example-component :user_id="{{ Auth::user()->id }}"/>
@endsection