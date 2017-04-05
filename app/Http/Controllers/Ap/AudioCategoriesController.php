<?php

namespace App\Http\Controllers\Ap;

use App\Http\Controllers\Controller;
use App\Models\AudioCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class AudioCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPageStr = $request->offsetExists('per_page') ? $request->offsetGet('per_page') : 10;

        $perPage = $perPageStr == 'all' ? 1000 : (int) $perPageStr;

        $model = AudioCategory::withTrashed()->orderBy('order', 'asc');

        if($request->offsetExists('query')) {
            $query = trim($request->offsetGet('query'));

            $model->where('id', 'like', '%' . $query . '%')
                ->orWhere('title', 'like', '%' . $query . '%');
        }

        $items = $model->paginate($perPage);

        $buttons = [['action' => action('Ap\AudioCategoriesController@create'), 'anchor' => '<i class="glyphicon glyphicon-file"></i>', 'title' => 'Add record']];

        return view('ap.AudioCategoriesIndex', [
            'buttons' => $buttons,
            'items'   => $items,
            'filter'  => [
                'per_page' => $perPage,
                'query' => $request->offsetGet('query')
            ],
            'current_per_page' => $perPageStr,
            'pers_page' => [ 10, 25, 50, 'all' ],
            'title'   => 'Audio Categories',
            'search' => true,
            'query'   => $request->offsetExists('query') ? $request->offsetGet('query') : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Session::set('referrer', URL::previous());

        return view('ap.AudioCategoryCreate', [
            'title' => 'Create Audio Category',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $result = AudioCategory::create($data);

        if ($result) {
            Session::flash('flash_message', 'Record was added');
        } else {
            Session::flash('flash_message', 'An error occurred try again later');
        }

        return redirect(Session::get('referrer'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Session::set('referrer', URL::previous());

        $item = AudioCategory::withTrashed()->findOrFail($id);

        return view('ap.AudioCategoryShow', [
            'item'  => $item,
            'title' => 'Edit audio category ' . $item->title,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Session::set('referrer', URL::previous());

        $item = AudioCategory::withTrashed()->findOrFail($id);

        return view('ap.AudioCategoryShow', [
            'item'  => $item,
            'title' => 'Edit audio category ' . $item->title,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = AudioCategory::withTrashed()->findOrFail($id);

        $data = $request->all();

        $result = $item->fill($data)->save();

        if ($result) {
            Session::flash('flash_message', 'Information sauvegardée');
        } else {
            Session::flash('flash_message', 'An error occurred try again later');
        }

        return redirect(Session::get('referrer'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = AudioCategory::findOrFail($id);
        $item->delete();

        Session::flash('flash_message', 'The record is deactivated');

        return redirect()->back();
    }

    /**
     * Restore record.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $item = AudioCategory::withTrashed()->findOrFail($id);
        $item->restore();

        Session::flash('flash_message', 'The record is activated');

        return redirect()->back();
    }


    public function updateOrder(Request $request){
        $list = $request->get("sort");
        if($list){
            $i = 1;
            $list = explode(',' , $list);
            foreach ($list as $id){
                $category = AudioCategory::withTrashed()->findOrFail($id);
                $category->update(["order" => $i]);
                $i++;
            }
        }
    }

    public function groups(AudioCategory $category){
        return view("ap.AudioGroupsInCategory",[
            "groups" => $category->groups()->withTrashed()->orderBy('order', 'asc')->get(),
            'title' => 'Audio groups in  ' . $category->title . " category"
        ]);
    }
}
