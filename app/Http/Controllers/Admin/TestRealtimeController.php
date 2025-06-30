<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\TestUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UploadConfiguration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;
use wapmorgan\UnifiedArchive\UnifiedArchive;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Core\UploadController as CoreUploadController;
use App\Mail\MailTemplate;
use Illuminate\Support\Facades\Mail;

class TestRealtimeController extends Controller
{
    public function index()
    {
        // https://www.itsolutionstuff.com/post/laravel-broadcast-redis-socket-io-tutorial-example.html
        return inertia('Admin/TestRealtime/Realtime');
    }

    public function send()
    {
        event(new \App\Events\SendChat(request()->form['user'],request()->form['chat']));
        return "Event has been sent!";
    }

}
