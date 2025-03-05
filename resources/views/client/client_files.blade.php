@extends('content')

@section('content')


    <client-files :case="{{json_encode($case)}}" :files="{{json_encode($caseFiles)}}" :folders="{{json_encode($folders)}}"></client-files>




@endsection
