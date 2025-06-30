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

class TestEmailController extends Controller
{
    public function index()
    {
        return inertia('Admin/TestEmail/Email');
    }

    public function testSendEmail()
    {
        $mailData = [
            'to' => 'to_your_email@gmail.com',
            'title' => 'Test Email',
        ];

        $files = TestUpload::where("type","Single in Multiple Data")->get()->map(function ($value) {
            return Storage::disk('public')->path(TestUpload::locationFile().'/'.$value['file1']);
        });

        Mail::send("email.template", $mailData, function($message)use($mailData, $files) {
            $message->to($mailData["to"])
                    ->subject($mailData["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }            
        });

        dd("Email is sent successfully.");
    }
}
