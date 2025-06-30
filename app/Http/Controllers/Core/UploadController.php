<?php

namespace App\Http\Controllers\Core;

use App\Models\UploadConfiguration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function information()
    {
        $fileConfiguration = UploadConfiguration::where('id', request()->id)->first();

        if (!empty($fileConfiguration)) {
            $fileConfiguration->ukuran_tampil = $this->formatFilesize($fileConfiguration->ukuran * 1024);
        }

        return response()->json([
            'success' => $fileConfiguration ? true : false,
            'data' => $fileConfiguration ?? null
        ]);
    }

    function formatFilesize($bytes)
    {
        $label = array('B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
        for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);
        return (round($bytes, 2) . " " . $label[$i]);
    }

    public function check()
    {
        $fileConfiguration = UploadConfiguration::where('id', request()->id_upload_configurations)->first();

        request()->validate([
            'files.*' => 'required|mimes:' . implode("", explode(" ", str_replace('.', '', $fileConfiguration->ekstensi))) . '|max:' . $fileConfiguration->ukuran,
        ], [
            'required' => 'File tidak boleh kosong',
            'mimes' => 'Format file yang didukung adalah ' . str_replace('.', '', $fileConfiguration->ekstensi),
            'max'      => 'File tidak boleh lebih dari ' . $this->formatFilesize($fileConfiguration->ukuran * 1024),
        ], [
            'files.*' => 'File',
        ]);

        return response()->json([
            'success' => true,
            'data' => true
        ]);
    }

    public function checkWithArguments($kolom,$data, $id)
    {
        $fileConfiguration = UploadConfiguration::where('id', $id)->first();
        Validator::make($data, [
            $kolom.'.*' => 'required|mimes:' . implode("", explode(" ", str_replace('.', '', $fileConfiguration->ekstensi))) . '|max:' . $fileConfiguration->ukuran,
        ], [
            'required' => 'File tidak boleh kosong',
            'mimes' => 'Format file yang didukung adalah ' . str_replace('.', '', $fileConfiguration->ekstensi),
            'max'      => 'File tidak boleh lebih dari ' . $this->formatFilesize($fileConfiguration->ukuran * 1024),
        ], [
            $kolom.'.*' => 'File',
        ])->validate();

        return true;
    }

    public function uploads($files,$folder)
    {
        if (is_array($files)) {
            $data = [];
            foreach ($files as $key => $value) {
                $name = time() . '-' . $value->getClientOriginalName();
                $value->storeAs('public/'.$folder, $name);
                array_push($data,$name);
            }
        }
        
        return $data;
    }

}
