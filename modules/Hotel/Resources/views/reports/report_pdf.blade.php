<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Habitaciones</title>
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
            <p align="center" class="title"><strong>Reporte Habitaciones</strong></p>
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
        @if (!empty($string))

            @php
                $rows = json_decode($string);
                $void = 0;
                $floor = '';
            @endphp

            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Habitación</th>
                                <th>Apellidos y Nombres</th>
                                <th>DNI</th>
                                <th>Fecha de Ingreso</th>
                                <th>Hora de Ingreso</th>
                                <th>Fecha de Salida</th>
                                <th>Hora de Salida</th>
                            </tr>
                         </thead>
                        <tbody>
                            @foreach ($rows as $row)

                                @if ($row -> status == true)
                                    
                                    <tr>

                                        <td class='celda'>{{ $row -> number }}</td>
                                        <td class="celda">{{ $row -> room }}</td>
                                        <td class='celda'>{{ $row -> rent_name}}</td>
                                        <td class='celda'>{{ $row -> dni}}</td>
                                        <td class='celda'>{{ $row -> input_date}}</td>
                                        <td class='celda'>{{ $row -> input_time}}</td>
                                        <td class='celda'>{{ $row -> output_date}}</td>
                                        <td class='celda'>{{ $row -> output_time}}</td>

                                    </tr>

                                @else

                                    @php
                                    
                                        $void += 1;

                                    @endphp

                                    <tr>

                                        <td class='celda success'>{{ $row -> number }}</td>
                                        <td class="celda success">{{ $row -> room }}</td>
                                        <td class='celda success'>{{ $row -> rent_name}}</td>
                                        <td class='celda success'>{{ $row -> dni}}</td>
                                        <td class='celda success'>{{ $row -> input_date}}</td>
                                        <td class='celda success'>{{ $row -> input_time}}</td>
                                        <td class='celda success'>{{ $row -> output_date}}</td>
                                        <td class='celda success'>{{ $row -> output_time}}</td>

                                    </tr>
                                                                    
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div style='margin-top: 2rem'>
                        <table style='width: 20%'>
                            <thead>
                                <tr>
                                    <th colspan='3'>Habitaciones Disponibles</th>
                                </tr>
                                <tr>
                                    <th>Piso</th>
                                    <th>Total</th>
                                    <th>Libres</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                    $free = 0;
                                    $floorName = '';
                                    $rows = json_decode($string);
                                @endphp
                                @foreach ($floors as $floor)
                                    @foreach ($rows as $row)

                                        @if (intval($row -> floor_id) == intval($floor -> id))
                                            @if ($row -> status == false)
                                                {{ $free = $free + 1 }}
                                            @endif
                                            {{$i = $i + 1}}
                                        @endif

                                    @endforeach
                                <tr>
                                    <th>{{ $floor -> description }}</th>
                                    <td class='celda'>{{ $i }}</td>
                                    <td class='celda'>{{ $free }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        @endif

    </body>
</html>