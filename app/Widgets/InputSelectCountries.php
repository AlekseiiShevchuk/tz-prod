<?php
/**
 * Created by PhpStorm.
 * User: mendel
 * Date: 20.01.17
 * Time: 16:19
 */

namespace App\Widgets;

use App\Models\Country;
use Illuminate\Contracts\View\View;

class InputSelectCountries
{
    public function compose(View $view)
    {
        $arg = $view->getData();

        $view->with([
            'countries' => Country::get(),
            'selected'   => $arg['country_id'] ?? 0,
            'class'   => $arg['class'] ?? 'form-control',
        ]);
    }
}