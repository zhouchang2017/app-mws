<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Scopes\NotificationWhereReadScope;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(subDomain() . ':auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function notifications(ErpRequest $request)
    {
        DatabaseNotification::addGlobalScope(new NotificationWhereReadScope());
        $notifications = $request->user()->notifications()->with('notifiable')->paginate();

        if ($request->ajax()) {
            return response()->json(
                $notifications
            );
        }
        return view('notifications.index', compact('notifications'));
    }

    public function notificationMakeAsRead($id, ErpRequest $request)
    {
        $notify = $request->user()->notifications()->find($id);
        return response()->json(
            $notify->markAsRead()
        );
    }
}
