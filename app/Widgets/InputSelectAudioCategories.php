<?php

namespace App\Widgets;

/**
 * Created by PhpStorm.
 * User: mendel
 * Date: 15.12.16
 * Time: 16:28
 */

use Illuminate\Contracts\View\View;

class InputSelectAudioCategories
{
    public function compose(View $view)
    {
        $arg = $view->getData();

        $view->with([
            'categories' => \App\Models\AudioCategory::get(),
            'selected'   => $arg['audio_categories_id'] ?? 0,
        ]);
    }
}