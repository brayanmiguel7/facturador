<?php

namespace App\Traits;
use App\Models\Tenant\Kardex; 
use App\Models\Tenant\Item; 
 


trait KardexTrait
{
    
    public function saveKardex($type, $item_id, $id, $quantity, $relation) {
        
        $kardex = Kardex::create([
            'type' => $type,
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $item_id,
            'document_id' => ($relation == 'document') ? $id : null,
            'purchase_id' => ($relation == 'purchase') ? $id : null,
            'sale_note_id' => ($relation == 'sale_note') ? $id : null,
            'quantity' => $quantity,
        ]);

        return $kardex;

    }

    public function updateStock($item_id, $quantity, $is_sale){

        $item = Item::find($item_id);
        $item->stock = ($is_sale) ? $item->stock - $quantity : $item->stock + $quantity;
        $item->save();
        
    }

}
