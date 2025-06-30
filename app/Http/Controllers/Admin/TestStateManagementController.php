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
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class TestStateManagementController extends Controller
{
    public function index()
    {
        return inertia('Admin/TestStateManagement/StateManagement');
    }

    public function detail()
    {
        return inertia('Admin/TestStateManagement/DetailStateManagement',[
            "title" => "Detail State Management"
        ]);
    }
}
