<?php

namespace Modules\LevelAccess\Http\ViewComposers;

use Modules\LevelAccess\Models\ModuleLevel;

class ModuleLevelViewComposer
{
    public function compose($view)
    {
        $module_levels = auth()->user()->levels()->pluck('value')->toArray();

        if(count($module_levels) > 0) {
            $view->vc_module_levels = $module_levels;
        } else {
            $view->vc_module_levels = ModuleLevel::all()->pluck('value')->toArray();
        }
    }
}
