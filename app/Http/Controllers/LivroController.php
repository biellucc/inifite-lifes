<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $vendedor = Auth::user()->vendedor;
        $livros = $vendedor->livros()->get();
        return view('vendedor.estoque.index' ,compact('livros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function formulario()
    {
        //$dimensoes = Dimensoes::all();
        $dimensoes = null;
        return view('vendedor.estoque.formulario', compact('dimensoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:100'],
            'resumo' => ['required', 'string', 'max:200'],
            'quantidade_paginas' => ['required', 'integer', 'min:1'],
            'autor' => ['required', 'string', 'max:100'],
            'estoque' => ['required', 'integer', 'min:1'],
            'valor' => ['required', 'numeric', 'min:1'],
            'imagem' => ['required', 'image', 'mimes:jpeg,jpg,png'],
            'isbn13' => ['required', 'string', 'regex:/^(97)[8-9]-[0-9]{2}-[0-9]{6}-[0-9]-[0-9]$/'],
            'idioma'=>['required', 'string', 'max:100'],
            'edicao'=> ['required', 'integer', 'min:1'],
            'editora' => ['required', 'string', 'max:80'],
            'idade'=> ['required', 'integer', 'min:5'],
            "data_publicacao" => ['required', 'date']
        ]);

        // Obtenha a instância da imagem
        $imagem = $request->file('imagem');

        // Gere um nome único para a imagem
        $nomeImagem = time() . '.' . $imagem->getClientOriginalExtension();

        // Mova a imagem para a pasta public/assets/imagem
        $imagem->move(public_path('assets/livro/imagem'), $nomeImagem);

        $vendedor = Auth::user()->vendedor;
        $livro = $vendedor->livros()->create([
            'titulo' => $request->titulo,
            'resumo' => $request->resumo,
            'quantidade_paginas' => $request->quantidade_paginas,
            'autor' => $request->autor,
            'estoque' => $request->estoque,
            'valor' => $request->valor,
            'isbn13' => $request->isbn13,
            'idioma'=> $request->idioma,
            'edicao'=> $request->edicao,
            'editora' => $request->editora,
            'dimensao' => $request->dimensao,
            'idade'=> $request->idade,
            "data_publicacao" => $request->data_publicacao,
            "imagem" => $nomeImagem,
            "genero_id" => $request->genero_id
        ]);

        return redirect(route('livro.livro', $livro->id));
    }

    /**
     * Display the specified resource.
     */
    public function livro(string $titulo = null, string $id) : View
    {
        $livro = Livro::find($id);
        return view('vendedor.estoque.livro', compact('livro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function atualizar(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deletar(string $id)
    {
        $livro = Livro::Find($id);
        $livro->status = 0;
        $livro->save();

        return redirect(route('estoque.index'));
    }
}