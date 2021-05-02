@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de inventarios</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('reports.inventory.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            {{-- <div class="el-form-item col-xs-12">
                                <div class="el-form-item__content">
                                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div> --}}
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">

                            <div style="margin-bottom: 10px" class="row">

                                <div style="padding-top: 0.5%" class="col-md-6">
                                    <form action="{{route('reports.inventory.index')}}" method="get">
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-8">
                                                <select class="form-control" name="warehouse_id" id="">
                                                    <option {{ request()->warehouse_id == 'all' ?  'selected' : ''}} selected value="all">Todos</option>
                                                    @foreach($warehouses as $item)
                                                    <option {{ request()->warehouse_id == $item->id ?  'selected' : ''}} value="{{$item->id}}">{{$item->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4"> <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button></div>
                                        </div>
                                    </form>
                                </div>
                                @if(isset($reports))
                                    <div class="col-md-4">
                                        <form action="{{route('reports.inventory.pdf')}}" class="d-inline" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id ? request()->warehouse_id : 'all'}}">
                                            <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                            {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                        </form>

                                        <form action="{{route('reports.inventory.report_excel')}}" class="d-inline" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id ? request()->warehouse_id : 'all'}}">
                                            <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                            {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                        </form>
                                    </div>

                                @endif


                            </div>
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                    <thead class="">
                                        <tr>
                                            <th>#</th>
                                            <th>Descripción</th>
                                            <th>U/M</th>
                                            <th>Categoria</th>
                                            <th>Inventario actual</th>
                                            <th>Precio de venta</th>
                                            <th>Precio de compra</th>
                                            <th>Marca</th>
                                            <th>F. vencimiento</th>
                                            <th>Almacén</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $key => $value)

                                        <tr>
                                            <td class="celda">{{$loop-> iteration}}</td>
                                            <td class="celda">{{$value->item->internal_id ?? ''}} {{$value->item->internal_id ? '-':''}} {{$value->item->description ?? ''}}</td>
                                            <td class='celda'>{{$value->item->unit_type_id}}</td>
                                            <td class="celda">{{optional($value->item->category)->name}}</td>

                                            @if(intval($value -> item -> stock) > intval($value->item->stock_min))
                                                <td class="celda success">{{$value->item->stock}}</td>
                                            @elseif(intval($value -> item -> stock) < intval($value->item->stock_min))
                                                <td class="celda danger">{{$value->item->stock}}</td>
                                            @endif

                                            <td class="celda">{{$value->item->sale_unit_price}}</td>
                                            <td class="celda">{{$value->item->purchase_unit_price}}</td>
                                            <td class="celda">{{ $value->item->brand->name }}</td>
                                            <td class="celda">{{ $value->item->date_of_due }}</td>
                                            <td class="celda">{{$value->warehouse->description}}</td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class='row'>
                                
                                <div class='col-2 text-justify'>Total {{$reports->total()}}</div>
                                <div class='col-4 text-center'>
                                    <div class='col-5 d-inline-block text-center text-white bg-success'>
                                        Suficiente
                                    </div>
                                    <div class='col-5 d-inline-block text-center text-white bg-danger'>
                                        Poca Cantidad
                                    </div>
                                </div>
                                
                            </div>
                            <label class="pagination-wrapper ml-2">
                                {{$reports->appends($_GET)->render()}}
                            </label>
                        </div>
                    </div>
                    @else
                    <div class="box box-body no-padding">
                        <strong>No se encontraron registros</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
