<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    const API_URL = "http://coba-api.test/api/buku";
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $current_url = url()->current();

        $client = new Client();
        $url = static::API_URL;
        if($request->input('page') != ''){
            $url .= '?page=' . $request->input('page');
        }
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $data = $contentArray['data'];
        foreach($data['links'] as $key => $value) {
            $data['links'][$key]['url2'] = str_replace(static::API_URL, $current_url, $value['url']);
        }
        return view('buku.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $judul = $request->judul;
        $pengarang = $request->pengarang;
        $tanggal_publikasi = $request->tanggal_publikasi;

        $parameter = [
            'judul' => $judul,
            'pengarang' => $pengarang,
            'tanggal_publikasi' => $tanggal_publikasi
        ];

        $client = new Client();
        $url = "http://coba-api.test/api/buku";
        $response = $client->request('POST', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if($contentArray['status'] != true){
            $error = $contentArray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        }else{
            return redirect()->to('buku')->with('success', 'Berhasil memasukkan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = new Client();
        $url = "http://coba-api.test/api/buku/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if($contentArray['status'] != true){
            $error = $contentArray['message'];
            return redirect()->back()->withErrors($error);
        }else{
            $data = $contentArray['data'];
            return view('buku.index', ['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $judul = $request->judul;
        $pengarang = $request->pengarang;
        $tanggal_publikasi = $request->tanggal_publikasi;

        $parameter = [
            'judul' => $judul,
            'pengarang' => $pengarang,
            'tanggal_publikasi' => $tanggal_publikasi
        ];

        $client = new Client();
        $url = "http://coba-api.test/api/buku/$id";
        $response = $client->request('PUT', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if($contentArray['status'] != true){
            $error = $contentArray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        }else{
            return redirect()->to('buku')->with('success', 'Berhasil melakukan update data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = new Client();
        $url = "http://coba-api.test/api/buku/$id";
        $response = $client->request('DELETE', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if($contentArray['status'] != true){
            $error = $contentArray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        }else{
            return redirect()->to('buku')->with('success', 'Berhasil melakukan delete data');
        }
    }
}
