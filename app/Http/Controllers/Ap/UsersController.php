<?php

namespace App\Http\Controllers\Ap;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use League\Flysystem\Exception;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Input::get('role', 'client');

        $perPageStr = $request->offsetExists('per_page') ? $request->offsetGet('per_page') : 10;

        $perPage = $perPageStr == 'all' ? 1000 : (int)$perPageStr;

        $model = User::withTrashed()->where('role', $role);

        if ($request->offsetExists('query')) {
            $query = trim($request->offsetGet('query'));

            if (!empty($query)) {
                $model->where('id', 'like', '%' . $query . '%')
                    ->orWhere('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('surname', 'like', '%' . $query . '%');
            }
        }

        $items = $model->paginate($perPage);

        $emailButton = [
            'action' => action('Ap\UsersController@sendEmail'),
            'anchor' => '<i class="glyphicon glyphicon-send"></i>',
            'title'  => 'Send email',
            'class'  => 'multiply_send_handler'
        ];

        $buttons = [[
            'action' => action('Ap\UsersController@create', ['role' => $role]),
            'anchor' => '<i class="glyphicon glyphicon-file"></i>',
            'title'  => 'Add record'
        ], $emailButton];

        if ($role == 'partner') {
            $title = 'Partners';
        } else {
            $title = 'Clients';
        }

        return view('ap.UsersIndex', [
            'buttons'          => $buttons,
            'items'            => $items,
            'filter'           => [
                'role'     => $role,
                'per_page' => $perPage,
                'query'    => $request->offsetGet('query')
            ],
            'role'             => $role,
            'title'            => $title,
            'pers_page'        => [10, 25, 50, 'all'],
            'current_per_page' => $perPageStr,
            'search'           => true,
            'query'            => $request->offsetExists('query') ? $request->offsetGet('query') : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item       = new User();
        $item->role = Input::get('role', 'client');

        Session::set('referrer', URL::previous());

        return view('ap.UserCreate', [
            'title' => 'Create user',
            'item'  => $item,
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
        return $this->saveUser($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, Response $response)
    {
        $item = User::withTrashed()->findOrFail($id);

        Session::set('referrer', URL::previous());

        return view('ap.UserShow', [
            'item'  => $item,
            'title' => 'Edit user ' . $item->name,
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
        $item = User::withTrashed()->findOrFail($id);

        return view('ap.UserShow', [
            'item'  => $item,
            'title' => 'Edit user ' . $item->name,
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
        $this->validate($request, [
            'nickname' => [Rule::unique('users')->ignore($id),],
            'phone'    => [Rule::unique('users')->ignore($id),]

        ], [
            'nickname.unique' => trans('membre.nickname_is_exists'),
            'phone.unique'    => trans('membre.phone_is_exists'),
        ]);

        $item = User::withTrashed()->findOrFail($id);

        return $this->saveUser($request, $item);
    }

    private function saveUser(Request $request, User $user = null)
    {

        $data = $request->all();

        $otherMessage = false;

        foreach ($data as $key => $item) {
            if ($item === '' && !in_array($key, ['nickname'])) {
                unset($data[$key]);
            }
        }// = null;

        if (isset($data['email'])) {
            $this->validate($request, [
                'email' => 'email'
            ]);
        }

        if (!isset($data['subscribe_news'])) {
            $data['subscribe_news'] = 0;
        }

        if (isset($data['password']) && !empty($data['password'])) {

            $this->validate($request, [
                'password' => 'min:6'
            ]);

            if (isset($data['confirm-password']) && $data['confirm-password'] != $data['password']) {
                Session::flash('flash_message', 'The passwords are different!');
                Session::flash('flash_message_type', 'error');

                return redirect()->back();
            }

            //$data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (isset($data['birthday']) && $data['birthday']) {
            $time = strtotime($data['birthday']);
            if ($time > -(90 * 365 * 24 * 60 * 60)) $data['birthday'] = date('Y-m-d', $time);
            else {
                $data['birthday'] = null;
                $otherMessage     = 'But date of birth is too small';
            }
        }

        if (isset($data['subscribe_access_to']) && $data['subscribe_access_to']) {
            $time = strtotime($data['subscribe_access_to']);

            if ($time > time())
                $data['subscribe_access_to'] = date('Y-m-d', $time);
            else {
                $data['subscribe_access_to'] = null;
                $otherMessage                = '"Subscribe access to" date must be in the future';
            }
        }

        $maxFileSizeMB = 10;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $size = $request->file('image')->getSize();
            if ($request->file('image')->getSize() < $maxFileSizeMB * 1024 * 1024) {
                $data['image'] = $request->file('image')->store('avatars', 'public');
            } else {
                Session::flash('flash_message', 'Maximum image size is ' . $maxFileSizeMB . 'MB');
                Session::flash('flash_message_type', 'error');

                return redirect()->back();
            }
        }
        if (empty($data['phone_country_code'])) $data['phone_country_code'] = '0';

        if (!empty($data['is_permanent_subscribe_access'])) $data['is_permanent_subscribe_access'] = true;
        else $data['is_permanent_subscribe_access'] = false;

        if (!empty($data['percent'])) {
            if ((int)$data['percent'] >= 100) {
                Session::flash('flash_message', 'Maximum percent is 100');

                return redirect()->back();
            }
        }

        try {
            if ($user) {
                $result = $user->fill($data)->save();
            } else {
                $data['password']    = str_random(10);
                $data['email_token'] = str_random(40);

                $data['role'] = empty($data['role']) ? User::DEFAULT_ROLE : $data['role'];

                $data['aid'] = $data['role'] == 'partner' ? rand(10000, 99999999) : '';

                $result = User::create($data);

                if ($data['role'] == 'client') {
                    \Mail::send('ap.emails.req', [
                        'email'               => $data['email'],
                        'name'                => $data['name'],
                        'url'                 => \URL::to('email/verif/' . $result->id . '/' . $data['email_token']),
                        'password'            => $data['password'],
                        'subscribe_access_to' => $data['subscribe_access_to'] ?? null,
                    ], function ($message) use ($data) {
                        $message->to($data['email']);
                        $message->subject('L’équipe de Turbulence Zéro vous offre un abonnement gratuit');
                    });
                }
            }
        } catch (QueryException $e) {
            $result = false;
            if (isset($e->errorInfo[2])) $error = $e->errorInfo[2];
        }

        if ($result) {
            Session::flash('flash_message', 'Information sauvegardée. ' . ($otherMessage ?: ''));

            $referrer = Session::get('referrer', $request->url());

            return redirect($referrer);

        } else {

            Session::flash('flash_message', $error ?: 'An error occurred try again later');
            Session::flash('flash_type', 'danger');
            Session::flash('flash_message_type', 'error');

            return redirect()->back();
        }
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

        $item = User::findOrFail($id);
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
        $item = User::withTrashed()->findOrFail($id);
        $item->restore();

        Session::flash('flash_message', 'The record is activated');

        return redirect()->back();
    }

    public function sendEmail(Request $request)
    {

        $requestEmails = $request->input('emails');

        if ($request->getMethod() == "POST") {

            $emails = [];

            $groupType     = $request->input('group');
            $email_message = $request->input('email_message');
            $subject       = $request->input('subject');

            if ($groupType == 'ids') {
                $emails = $request->input('email');
                $emails = explode(',', $emails);
            } elseif ($groupType == 'all') {
                $users = User::select('email')->where('deleted_at', null)->get();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
            } elseif ($groupType == 'client') {
                $users = User::select('email')->where('deleted_at', null)->where('role', 'client')->get();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
            } elseif ($groupType == 'partner') {
                $users = User::select('email')->where('deleted_at', null)->where('role', 'partner')->get();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
            }

//            var_dump($request->all());
//            var_dump($emails);

            try {
                if ($emails) foreach ($emails as $email) {
                    Mail::send('ap.emails.default', ['text' => $email_message], function ($message) use ($email, $subject) {
                        $message->to($email);
                        $message->subject($subject);
                    });
                }

                Session::flash('flash_message', 'Mails sended');

            } catch (\Exception $e) {
                Session::flash('flash_error', 'Mails failed');
            }

            $requestEmails = '';

        } else {

            $ids = $request->input('ids');

            if ($ids) {

                $emails = [];

                $users = User::select('email')->whereIn('id', explode(',', $ids))->get();

                if ($users) foreach ($users as $user) {
                    $emails[] = $user->email;
                }

                if ($emails) $requestEmails = implode(',', $emails);

            }

        }

        return view('ap.SendEmail', [
            'title'  => 'Send mail',
            'emails' => $requestEmails
        ]);
    }

    public function findByEmail(Request $request)
    {
        $query = $request->input('query');

        $users = [];

        if (strlen(trim($query)) > 3) {
            $users = User::select('email', 'name')->where('email', 'like', '%' . $query . '%')->get();
        }

        return response()->json($users);
    }
}