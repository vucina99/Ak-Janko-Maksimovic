@extends("content")

@section('content')


    <staff :is_admin="{{Auth::user()->id}}"></staff>


@endsection
