<?php

namespace Modules\Hotel\Http\Controllers;

use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\ItemWarehouse;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hotel\Models\HotelRoom;
use Modules\Hotel\Models\HotelFloor;
use Modules\Hotel\Models\HotelRent;

class HotelReceptionController extends Controller
{
	public function index()
	{
		$rooms = $this->getRooms();

		if (request()->ajax()) {
			return response()->json([
				'success' => true,
				'rooms'   => $rooms,
			], 200);
		}
		$floors = HotelFloor::where('active', true)
				->orderBy('description')
				->get();

		$roomStatus = HotelRoom::$status;

		return view('hotel::rooms.reception', compact('rooms', 'floors', 'roomStatus'));
	}

	private function getRooms()
	{
		$rooms = HotelRoom::with('category', 'floor', 'rates');

		if (request('hotel_floor_id')) {
			$rooms = $rooms->where('hotel_floor_id', request('hotel_floor_id'));
		}
		if (request('status')) {
			$rooms = $rooms->where('status', request('status'));
		}

		$rooms = $rooms->get()->each(function ($room) {
			if ($room->status === 'OCUPADO') {
				$rent = HotelRent::where('hotel_room_id', $room->id)
					->orderBy('id', 'DESC')
					->first();
				$room->rent = $rent;
			} else {
				$room->rent = [];
			}

			return $room;
		});

		return $rooms;
	}

	public function pdf(){

		$company = Company::first();
        $establishment = Establishment::first();
        ini_set('max_execution_time', 0);

        $rents = HotelRent::all();
		$rooms = HotelRoom::all();
		$floors = HotelFloor::all();

		$json = array();
		$i = 1;
		foreach($rooms as $room){

			if($room -> status == 'OCUPADO'){

				$rent_data = array();
				$name = '';
				$dni = '';
				foreach($rents as $rent){

					if(intval($room -> id) == intval($rent -> hotel_room_id)){

						$name = $rent -> customer -> name;
						$dni = $rent -> customer -> number;
						$input_date = $rent -> input_date;
						$input_time = $rent -> input_time;
						$output_date = $rent -> output_date;
						$output_time = $rent -> output_time;

					}

				}

				$encode = json_encode($rent_data);
				$rent = json_decode($encode);
				$json[] = array(

					'number' => $i,
					'room' => $room -> name,
					'floor_id' => $room -> hotel_floor_id,
					'status' => true,
					'rent_name' => $name,
					'dni' => $dni,
					'input_date' => $input_date,
					'input_time' => $input_time,
					'output_date' => $output_date,
					'output_time' => $output_time

				);

			} else {

				$json[] = array(

					'number' => $i,
					'room' => $room -> name,
					'floor_id' => $room -> hotel_floor_id,
					'status' => false,
					'rent_name' => '',
					'dni' => '',
					'input_date' => '',
					'input_time' => '',
					'output_date' =>'',
					'output_time' => ''

				);

			}

		}

		$string = json_encode($json);
		$pdf = PDF::loadView('hotel::reports.report_pdf', compact("string", "company", "establishment", "floors"));
        $pdf->setPaper('A4', 'landscape');
        $filename = 'Reporte_Habitaciones'.date('YmdHis');

        return $pdf->download($filename.'.pdf');

	}

}
