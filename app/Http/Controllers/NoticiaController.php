<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticia;

class NoticiaController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }

    public function index() {
        $noticias = Noticia::where('user_id', auth()->user()->id)->get();

        return view('dashboard', [
            'noticias' => $noticias
        ]);
    }

    public function search(Request $request) {
        $noticias = Noticia::where('titulo', 'like', '%' . $request->termoPesquisa . '%')->get();

        return view('dashboard', [
            'noticias' => $noticias
        ]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'titulo' => 'required',
            'corpo' => 'required'
        ]);

        auth()->user()->noticias()->create([
            'titulo' => $request->titulo,
            'corpo' => $request->corpo
        ]);

        return back();
    }

    public function update(Request $request) {
        $this->validate($request, [
            'titulo' => 'required',
            'corpo' => 'required'
        ]);

        auth()->user()->noticias()->where('id', $request->id)->update([
            'titulo' => $request->titulo,
            'corpo' => $request->corpo
        ]);

        return back();
    }

    public function destroy(Noticia $noticia) {
        $noticia->delete();

        return back();
    }
}
