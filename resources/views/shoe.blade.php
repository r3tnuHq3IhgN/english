@extends('layouts.app')
@section('content')
<shoe :user_id="{{ Auth::user()->id }}"/>
@endsection