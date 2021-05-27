<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index()
    {
        // DB::enableQueryLog();
        $articles = Article::leftjoin('users', 'articles.user_id', '=', 'users.id')
                        ->select(
                            'users.id as user_id', 
                            'users.name',
                            'articles.id as article_id',
                            'articles.title',
                            'articles.body',
                            'articles.user_id as article_user_id',
                            'articles.created_at'
                            )
                        ->orderBy('articles.created_at', 'desc')
                        ->get();

        // $article = DB::table('articles')->select('articles.id as article_id', 'title', 'body', 'user_id','users.id', 'users.name')->leftjoin('users', 'articles.user_id', '=', 'users.id')->orderBy('articles.created_at', 'desc')->get();
        // $articles = json_decode(json_encode($article), true);
        //ddd($articles);
        // ddd(DB::getQueryLog());
        return view('articles.index', compact('articles', 'article2'));
    }

    public function create()
    {
        return view('articles.create');    
    }

    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();
        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        return view('articles.edit', ['article' => $article]);    
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();
        return redirect()->route('articles.index');
    }
}
