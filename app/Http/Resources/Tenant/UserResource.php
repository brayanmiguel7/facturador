<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Module;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $all_modules = Module::orderBy('order_menu')->get();
        $modules_in_user = $this->modules->pluck('id')->toArray();
        $levels_in_user = $this->levels->pluck('id')->toArray();
        $modules = [];
        $levels = [];
        $dataSource=[];

        foreach ($all_modules as $module)
        {
            $check = false;
            if(in_array($module->id, $modules_in_user)){

                if(count($module->levels)>0){
                    $check = false;
                }else{
                    $check = true;
                }
            }
            $dataSource[]=[
                'id' => $module->id,
                'description' => $module->description,
                'isChecked' => (bool)  $check,
                'hasChild'=> (bool) in_array($module->id, $modules_in_user),
                'expanded'=>(bool) in_array($module->id, $modules_in_user)
            ];
            $modules[] = [
                'id' => $module->id,
                'description' => $module->description,
                'checked' => (bool) in_array($module->id, $modules_in_user)
            ];

            if(in_array($module->id, $modules_in_user)){

                foreach ($module->levels as $level) {
                    $dataSource[]=[
                        'id' =>$module->id.'-'.   $level->id,
                        'pid'=>$module->id,
                        'description' => $level->description,
                        'isChecked' => (bool) in_array($level->id, $levels_in_user)
                    ];
                    $levels[] = [
                        'id' => $level->id,
                        'description' => $level->description,
                        'checked' => (bool) in_array($level->id, $levels_in_user)
                    ];
                }

            }
        }
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'api_token' => $this->api_token,
            'establishment_id' => $this->establishment_id,
            'type' => $this->type,
            'modules' => $modules,
            'levels' => $levels,
            'locked' => (bool) $this->locked,
            'dataSource'=>$dataSource

        ];
    }
}
