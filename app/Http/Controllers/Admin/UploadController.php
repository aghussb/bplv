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
use Illuminate\Support\Facades\Mail;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = UploadConfiguration::with("menus")->where('keterangan', 'like', '%' . request()->cari . '%')
            ->orderBy("id")->paginate(10);

        $uploads->getCollection()->transform(function ($value) {

            $value->ekstensi_value = explode(", ", $value->ekstensi);

            return $value;
        });


        $dataEkstensi = [
            ".jpg",
            ".psd",
            ".docx",
            ".xls",
            ".xlsx",
            ".zip",
            ".doc",
            ".bmp",
            ".pdf",
            ".ppt",
            ".png",
            ".rar",
            ".jpeg",
        ];

        $menus = Menu::orderBy('urutan', 'asc')->get();

        // dd($users->toArray());
        return inertia('Admin/Upload/Upload', [
            'uploads' => $uploads,
            'ekstensi' => $dataEkstensi,
            'menus' => $menus
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->form);
        $form = $request->form;

        $this->validate(
            $request,
            [
                'form.keterangan' => 'required',
                'form.ekstensi' => "required|array|min:1",
                'form.ukuran' => 'required|not_in:0',
            ],
            [
                'form.keterangan' => ['required' => 'Keterangan belum diisi',],
                'form.ekstensi' => ['required' => 'Ekstensi belum dipilih',],
                'form.ukuran' => [
                    'required' => 'Ukuran belum diisi',
                    'not_in' => 'Ukuran belum diisi',
                ],
            ],
            []
        );

        DB::beginTransaction();
        try {
            $form['ekstensi'] = implode(", ", $form['ekstensi']);
            UploadConfiguration::updateOrCreate(['id' => $form['id'] ?? null], $form);

            DB::commit();
            return redirect()->route('upload');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            UploadConfiguration::find(request()->id)->delete();
            DB::commit();
            return redirect()->route('upload');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function indexTestUpload()
    {
        $dataTestUpload = TestUpload::get();

        return inertia('Admin/TestUpload/Upload', [
            'data' => $dataTestUpload,
        ]);
    }

    public function storeTestUpload(Request $request)
    {

        DB::beginTransaction();
        try {
            // dd($request->data);
            if ($request->type == "Single Basic") {
                if ((new CoreUploadController())->checkWithArguments("files", $request->file(), $request->id_file_configurations)) {

                    if (!empty($request->data)) {
                        Storage::disk('local')->delete('public/' . TestUpload::locationFile() . '/' . $request->data['file1']);
                    }

                    $dataUploaded = (new CoreUploadController)->uploads($request->file("files"), TestUpload::locationFile());
                    $obj = [
                        "file1" => $dataUploaded[0],
                        "type" => $request->type,
                    ];

                    TestUpload::updateOrCreate(['id' => $request->data['id'] ?? null], $obj);
                }
            } else if ($request->type == "Single Input") {
                if ((new CoreUploadController())->checkWithArguments("files", $request->file(), $request->id_file_configurations)) {

                    if (!empty($request->data)) {
                        Storage::disk('local')->delete('public/' . TestUpload::locationFile() . '/' . $request->data['file1']);
                    }

                    $dataUploaded = (new CoreUploadController)->uploads($request->file("files"), TestUpload::locationFile());
                    $obj = [
                        "file1" => $dataUploaded[0],
                        "type" => $request->type,
                    ];

                    TestUpload::updateOrCreate(['id' => $request->data['id'] ?? null], $obj);
                }
            } else if ($request->type == "Single in Multiple Data") {
                if (count($request->file()) != 0) {
                    if ((new CoreUploadController())->checkWithArguments("data_file", $request->file(), $request->id_file_configurations)) {
                        $dataUploaded = (new CoreUploadController)->uploads($request->file("data_file"), TestUpload::locationFile());
                        foreach ($dataUploaded as $key => $value) {
                            $obj = [
                                "file1" => $value,
                                "type" => $request->type,
                            ];
                            TestUpload::insert($obj);
                        }
                    }
                }

                if (!empty($request->data_hapus)) {
                    foreach ($request->data_hapus as $key => $value) {
                        TestUpload::find($value['id'])->delete();
                        Storage::disk('local')->delete('public/' . TestUpload::locationFile() . '/' . $value['file1']);
                    }
                }
            } else if ($request->type == "Single Object") {
                $dataFile1 = (!empty($request->data)) ? $request->data['file1'] : null;
                $dataFile2 = (!empty($request->data)) ? $request->data['file2'] : null;
                $dataFile3 = (!empty($request->data)) ? $request->data['file3'] : null;

                if (!empty($request->file("files")["satu"])) {
                    if (!empty($request->data)) {
                        Storage::disk('local')->delete('public/' . TestUpload::locationFile() . '/' . $request->data['file1']);
                    }

                    if ((new CoreUploadController())->checkWithArguments("satu", $request->file("files"), $request->id_file_configurations_object1)) {
                        $dataUploaded1 = (new CoreUploadController)->uploads($request->file("files")['satu'], TestUpload::locationFile());
                        $dataFile1 = $dataUploaded1[0];
                    }
                }

                if (!empty($request->file("files")["dua"])) {
                    if (!empty($request->data)) {
                        Storage::disk('local')->delete('public/' . TestUpload::locationFile() . '/' . $request->data['file2']);
                    }

                    if ((new CoreUploadController())->checkWithArguments("dua", $request->file("files"), $request->id_file_configurations_object2)) {
                        $dataUploaded2 = (new CoreUploadController)->uploads($request->file("files")['dua'], TestUpload::locationFile());
                        $dataFile2 = $dataUploaded2[0];
                    }
                }

                if (!empty($request->file("files")["tiga"])) {
                    if (!empty($request->data)) {
                        Storage::disk('local')->delete('public/' . TestUpload::locationFile() . '/' . $request->data['file3']);
                    }

                    if ((new CoreUploadController())->checkWithArguments("tiga", $request->file("files"), $request->id_file_configurations_object3)) {
                        $dataUploaded3 = (new CoreUploadController)->uploads($request->file("files")['tiga'], TestUpload::locationFile());
                        $dataFile3 = $dataUploaded3[0];
                    }
                }

                $obj = [
                    "file1" => $dataFile1,
                    "file2" => $dataFile2,
                    "file3" => $dataFile3,
                    "type" => $request->type,
                ];

                TestUpload::updateOrCreate(['id' => $request->data['id'] ?? null], $obj);
            }
            DB::commit();
            return redirect()->route('testUpload');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteTestUpload()
    {
        DB::beginTransaction();
        try {
            if (request()->type == "Single Basic" || request()->type == "Single Input") {
                Storage::disk('local')->delete('public/' . TestUpload::locationFile() . '/' . request()->data['file1']);
                TestUpload::find(request()->data['id'])->delete();
            }

            DB::commit();
            return redirect()->route('testUpload');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function zippedFiles(Request $request)
    {
        if (!empty($request->data_file)) {

            $object = [];
            foreach ($request->data_file as $item) {
                $object[$item['file1']] = Storage::disk('public')->path(TestUpload::locationFile().'/'.$item['file1']);
            }
            
            UnifiedArchive::create($object, Storage::disk('public')->path(TestUpload::locationFile() . '/'.time().'-archive.zip'));

            return redirect()->route('testUpload');
        }
    }
}
