<?php

namespace App\Http\Controllers\Ap;

use App\Http\Controllers\Controller;
use App\Models\AudioGroup;
use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SoundController extends Controller
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

        $model = Sound::withTrashed()->orderBy('order', 'asc');

        if($request->offsetExists('query')) {
            $query = trim($request->offsetGet('query'));

            if (!empty($query)) {
                $model->where('id', 'like', '%' . $query . '%')
                    ->orWhere('title', 'like', '%' . $query . '%')
                    ->orWhereHas('group', function($q) use ($query) {
                        $q->where('title', 'like', '%' . $query . '%');
                    })
                    ->orWhereHas('group', function($q) use ($query) {
                        $q->where('title', 'like', '%' . $query . '%')
                            ->orWhereHas('category', function($q) use ($query) {
                                $q->where('title', 'like', '%' . $query . '%');
                            });
                    });
            }
        }

        $items = $model->paginate($perPage);

        $buttons = [['action' => action('Ap\SoundController@create'), 'anchor' => '<i class="glyphicon glyphicon-file"></i>', 'title' => 'Add record']];

        return view('ap.SoundsIndex', [
            'buttons' => $buttons,
            'items'   => $items,
            'filter'  => [
                'per_page' => $perPage,
                'query' => $request->offsetGet('query')
            ],
            'pers_page' => [ 10, 25, 50, 'all' ],
            'current_per_page' => $perPageStr,
            'title'   => 'Sounds',
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

        return view('ap.SoundCreate', [
            'title' => 'Create Sound',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['url'] = '/' . Config::get('app.sounds_dir') . trim($data['audio_file_name']);

        $data['duration'] = Sound::estimateDuration(public_path(mb_substr($data['url'], 1)));

        unset($data['audio_file_name']);

//        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid() && $request->file('audio_file')->getMimeType() == "audio/mpeg") {
//            $newFileName = md5(date('U')) . '.mp3';
//
//            $request->file('audio_file')->move(base_path('public/') . Config::get('app.sounds_dir'), $newFileName);
//
//            unset($data['audio_file']);
//
//            $data['url'] = '/' . Config::get('app.sounds_dir') . $newFileName;
//        } else {
//            Session::flash('flash_message', 'Please, add mp3 file');
//
//            return redirect()->action('SoundController@index');
//        }

        $result = Sound::create($data);

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
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Session::set('referrer', URL::previous());

        $item = Sound::withTrashed()->findOrFail($id);

        return view('ap.SoundShow', [
            'item'  => $item,
            'title' => 'Edit sound ' . $item->title,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Session::set('referrer', URL::previous());

        $item = Sound::withTrashed()->findOrFail($id);

        return view('ap.SoundShow', [
            'item'  => $item,
            'title' => 'Edit sound ' . $item->title,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Sound::withTrashed()->findOrFail($id);

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
        $item = Sound::findOrFail($id);
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
        $item = Sound::withTrashed()->findOrFail($id);
        $item->restore();

        Session::flash('flash_message', 'The record is activated');

        return redirect()->back();
    }

    public function updateOrder(AudioGroup $group,Request $request){
        $list = $request->get("sort");
        if($list){
            $sounds = $group->sounds()->withTrashed()->get();
            $list = array_flip(explode(',' , $list));
            foreach ($sounds as $sound){
                $order = $list[$sound->id] + 1;
                $sound->update([
                    "order" => $order
                ]);
            }
        }
    }
}
