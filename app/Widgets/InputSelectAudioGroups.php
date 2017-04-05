<?php

namespace App\Widgets;

/**
 * Created by PhpStorm.
 * User: mendel
 * Date: 15.12.16
 * Time: 16:28
 */

use Illuminate\Contracts\View\View;
use App\Models\AudioGroup;

class InputSelectAudioGroups
{
    public function compose(View $view)
    {
        $arg = $view->getData();

        $view->with([
            'groups' => AudioGroup::get(),
            'selected'   => $arg['audio_groups_id'] ?? 0,
        ]);
    }
}