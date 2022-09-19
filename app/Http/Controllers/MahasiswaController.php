<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function cetak_pdf()
    {
        $data = Mahasiswa::all();
        return view('cetak_pdf', compact('data'));
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

        return redirect('mahasiswa/index')->with('success', 'Mahasiswa Berhasil Disimpan');
    }

    public function show($id)
    {

    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('edit', ['mahasiswa' => $mahasiswa]);
    }

    public function update(Request $request, $mahasiswa)
    {
        $rules = [
            'name' => 'string|max:50',
            'username' => 'alpha_dash|min:4|max:20|unique:App\Models\Mahasiswa',
            'email' => 'email:dns|unique:App\Models\Mahasiswa',
            'password' => 'min:5',
            'avatar' => 'image|mimes:jpg,jpeg,png'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()){
            return view('create')->with('error', $validator->errors());
        }

        $file = $request->file('avatar');
        $image_name = '';

        if($file){
            $image_name = $file->store('images', 'public');
            if(Storage::exists('public/'.$data->avatar)){
                Storage::delete('public/'.$data->avatar);
            }
        }

        if(!empty($request->file('avatar'))){
            Mahasiswa::where('id_mahasiswa', $mahasiswa)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $image_name
            ]);
        }else{
            Mahasiswa::where('id_mahasiswa', $mahasiswa)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect('mahasiswa/index')->with('mahasiswa', $mahasiswa)->with('success', 'data berhasil diupdate');
    }

    public function destroy($mahasiswa)
    {
        Mahasiswa::where('id_mahasiswa',$mahasiswa)->delete();
        return redirect()->back()->with('success','Berhasil menghapus data');
    }
    // public function cetak_pdf()
    // {
    //     $data = Mahasiswa::all();
    //     $pdf = Pdf::loadView('cetak_pdf', ['data' => $data]);
    //     return $pdf->stream();
    // }
}
