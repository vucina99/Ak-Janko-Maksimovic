@extends('content')

@section('content')


    <search-result-vansudski :is_admin="{{Auth::user()->role_id}}"></search-result-vansudski>




@endsection
