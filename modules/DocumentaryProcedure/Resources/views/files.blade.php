@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-files :files='@json($files)'></tenant-documentary-files>
@endsection
