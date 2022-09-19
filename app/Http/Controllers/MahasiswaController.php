<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index()
    {
        $data = Mahasiswa::get();
        return view('index', compact('data'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'username' => 'required|alpha_dash|min:4|max:20|unique:App\Models\Mahasiswa',
            'email' => 'required|email:dns|unique:App\Models\Mahasiswa',
            'password' => 'required|min:5',
            'avatar' => 'required|image|mimes:jpg,jpeg,png'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()){
            return view('create')->with('error', $validator->errors());
        }

        $file = $request->file('avatar');
        $image_name = $file->getClientOriginalName();

        if($file){
            $image_name = $file->store('images', 'public');
        }

        Mahasiswa::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $image_name
        ]);

        return view('index')->with('success', 'Mahasiswa Berhasil Disimpan');
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $data = Mahasiswa::find($id);

        return (!data)? view('no_data') :
        view('edit')->with('id', $id)->with('data', $data);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'username' => 'required|alpha_dash|min:4|max:20|unique:App\Models\Mahasiswa',
            'email' => 'required|email:dns|unique:App\Models\Mahasiswa',
            'password' => 'required|min:5',
            'avatar' => 'required|image|mimes:jpg,jpeg,png'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()){
            return view('create')->with('error', $validator->errors());
        }

        $file = $request->file('avatar');
        $image_name = $file->getClientOriginalName();

        if($file){
            $image_name = $file->store('images', 'public');
            if(Storage::exists('public/'.$data->avatar)){
                Storage::delete('public/'.$data->avatar);
            }
        }

        Mahasiswa::where('id_mahasiswa', $id)->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'avatar' => $image_name
        ]);

        return view('edit')->with('id', $id)->with('data', $data)
                           ->with('success', 'data berhasil diupdate');
    }

    public function cetak_pdf()
    {
        $data = Mahasiswa::all();
        $pdf = Pdf::loadView('cetak_pdf', ['data' => $data]);
        return $pdf->stream();
    }
}
