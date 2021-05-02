@extends('tenant.layouts.app')

@section('content')
    <div class="container-fluid mx-0">
        <div class="row">
            <div class="col-6">
                <form action="{{ route('reception.report_rooms.pdf') }}" class="d-inline" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id ? request()->warehouse_id : 'all'}}">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-file-pdf"></i> Reporte PDF</button>
                </form>
            </div>
        </div>
    </div>
    <tenant-hotel-reception :floors='@json($floors)' :room-status='@json($roomStatus)' :rooms='@json($rooms)'></tenant-hotel-reception>
@endsection
