<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Inventario</title>
        <style>
            html {
                font-family: sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-spacing: 0;
                border: 1px solid black;
            }

            .celda {
                text-align: center;
                padding: 5px;
                font-size: 10px;
                border: 0.1px solid black;
            }

            th {
                font-size: 10px;
                padding: 5px;
                text-align: center;
                border-color: #0088cc;
                border: 0.1px solid black;
            }

            .title {
                font-weight: bold;
                padding: 5px;
                font-size: 20px !important;
                text-decoration: underline;
            }

            p>strong {
                margin-left: 5px;
                font-size: 13px;
            }

            thead {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: center;
            }
            .success {

                background-color: rgb(62, 185, 64);
                color: white;

            }
            .danger {

                background-color: rgb(225, 44, 44);
                color: white;

            }
        </style>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>Reporte Inventario</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td>
                        <p><strong>Empresa: </strong>{{$company->name}}</p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong>{{date('Y-m-d')}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong>{{$company->number}}</p>
                    </td>
                    <td>
                        <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
                    </td>
                </tr>
            </table>
        </div>
        @if(!empty($reports))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th colspan='6'>Productos</th>
                                <th class='celda success' colspan="3">ENTRADA</th>
                                <th class='celda danger' colspan="3">SALIDA</th>
                                <th class='celda primary' colspan="3">SALDOS</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Código</th>
                                <th>U/M</th>
                                <th>Categoria</th>
                                <th>Cantidad</th>
                                <th>Costo de compra</th>
                                <th>Costo total</th>
                                <th>Cantidad</th>
                                <th>Costo de venta</th>
                                <th>Total</th>
                                <th>Cantidad actual</th>
                                <th>Costo de venta actual</th>
                                <th>Total en inventario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $total_sale_price = 0;
                            @endphp
                            @foreach($reports as $key => $value)
                                @php
                                    $total += $value->item->purchase_unit_price;
                                    $total_sale_price += floatval($value -> item -> init_stock) * floatval($value->item->purchase_unit_price);
                                @endphp
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{ $value -> item -> name }}</td>
                                    <td class="celda">{{$value->item->description ?? ''}}</td>
                                    <td class='celda'>{{$value->item->internal_id}}</td>
                                    <td class='celda'>{{$value->item->unit_type_id}}</td>
                                    <td class="celda">{{optional($value->item->category)->name}}</td>
                                    <td class="celda">{{ floatval($value -> item -> init_stock) }}</td>
                                    <td class="celda">{{floatval($value->item->purchase_unit_price)}} S/</td>
                                    <td class="celda">{{ floatval($value -> item -> init_stock) * floatval($value->item->purchase_unit_price) }} S/</td>
                                    
                                    <td class="celda">{{ $value -> item -> init_stock - $value -> item -> stock }}</td>
                                    <td class="celda">{{ floatval($value -> item -> sale_unit_price) }} S/</td>
                                    <td class="celda">{{ ($value -> item -> init_stock - $value -> item -> stock) * $value -> item -> sale_unit_price }} S/</td>

                                    @if(intval($value -> item -> stock) > $value -> item -> stock_min)
                                        <td class="celda success">{{intval($value-> item -> stock)}}</td>
                                    @elseif(intval($value -> item -> stock) <= $value -> item -> stock_min)
                                        <td class="celda danger">{{intval($value-> item -> stock)}}</td>
                                    @endif

                                    <td class="celda">{{ floatval($value -> item -> sale_unit_price) }} S/</td>
                                    <td class="celda">{{ floatval(intval($value -> item -> stock) * $value -> item -> sale_unit_price) }}S/</td>

                                </tr>
                            @endforeach
                            <tr>
                                <td class="celda" colspan="7" style="text-align: right;">
                                    <strong> Costo Total de Inventario</strong>
                                </td>
                                <td class="celda">{{ number_format($total, 2) }} S/</td>
                                <td class="celda">{{ number_format($total_sale_price, 2) }} S/</td>
                                <td class="celda"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div style='margin-top: 2rem'>
                        <table style='width: 20%'>
                            <thead>
                                <tr>
                                    <th colspan='2'>Leyenda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Encima de límite</td>
                                    <td class='celda success' style='padding: 5px'></td>
                                </tr>
                                <tr>
                                    <td>Debajo de límite</td>
                                    <td class='celda danger' style='padding: 5px'></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="callout callout-info">
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
