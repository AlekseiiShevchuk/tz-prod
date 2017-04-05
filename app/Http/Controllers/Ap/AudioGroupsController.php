<?php

namespace App\Http\Controllers\Ap;

use App\Http\Controllers\Controller;
use App\Models\AudioGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\AudioCategory;
use Illuminate\Support\Facades\URL;

class AudioGroupsController extends Controller
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

        $model = AudioGroup::withTrashed()->orderBy('order', 'asc');

        if($request->offsetExists('query')) {
            $query = trim($request->offsetGet('query'));

            $model->where('id', 'like', '%' . $query . '%')
                ->orWhere('title', 'like', '%' . $query . '%')
                ->orWhereHas('category', function($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%');
                });
        }

        $items = $model->paginate($perPage);

        $buttons = [['action' => action('Ap\AudioGroupsController@create'), 'anchor' => '<i class="glyphicon glyphicon-file"></i>', 'title' => 'Add record']];

        return view('ap.AudioGroupsIndex', [
            'buttons' => $buttons,
            'items'   => $items,
            'filter'  => [
                'per_page' => $perPage,
                'query' => $request->offsetGet('query')
            ],
            'current_per_page' => $perPageStr,
            'pers_page' => [ 10, 25, 50, 'all' ],
            'title'   => 'Audio Groups',
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

        return view('ap.AudioGroupCreate', [
            'title' => 'Create Audio Group',
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

        $result = AudioGroup::create($data);

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

        $item = AudioGroup::withTrashed()->findOrFail($id);

        return view('ap.AudioGroupShow', [
            'item'  => $item,
            'title' => 'Edit audio group ' . $item->title,
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

        $item = AudioGroup::withTrashed()->findOrFail($id);

        return view('ap.AudioGroupShow', [
            'item'  => $item,
            'title' => 'Edit audio group ' . $item->title,
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
        $item = AudioGroup::withTrashed()->findOrFail($id);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = AudioGroup::findOrFail($id);
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
        $item = AudioGroup::withTrashed()->findOrFail($id);
        $item->restore();

        Session::flash('flash_message', 'The record is activated');

        return redirect()->back();
    }

    public function sounds(AudioGroup $group){
        return view("ap.AudioSoundsInGroup",[
            "sounds" => $group->sounds()->withTrashed()->orderBy('order', 'asc')->get(),
            'title' => 'Audio sounds in  ' . $group->title . " group"
        ]);
    }

    public function updateOrder(AudioCategory $category,Request $request){
        $list = $request->get("sort");
        if($list){
            $groups = $category->groups()->withTrashed()->get();
            $list = array_flip(explode(',' , $list));
            foreach ($groups as $group){
                $order = $list[$group->id] + 1;
                $group->update([
                    "order" => $order
                ]);
            }
        }
    }
}
