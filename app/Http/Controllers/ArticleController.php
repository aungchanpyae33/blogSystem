<?php

namespace App\Http\Controllers;
USE App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{   
    public function __construct(){
        $this->middleware('auth')->except([
            'index' , 'detail'
        ]);
    }
     
    public function index(){
        // $data = Article::all(); //get data from phpadamin
        $data = Article::latest()->paginate(5);
        return view("articles.index" ,[
            "articles" => $data,
        ]);
    }


    public function detail($id){
        $article = Article::find($id);
        return view("articles.detail" , ["article" => $article]);
}
    public function delete($id){
        $article = Article::find($id);

        if(Gate::allows("delete->article" , $article)){
            $article->delete();
            return redirect("/articles")->with("info" , "Article Deleted");
        }
        return back()->with("info" , "Unauthorize");
    }
    
    public function edit($id){
        $article = Article::find($id);
        $data = Category::all();
        return view("articles.edit" ,
         ["article" => $article,
         "categories" => $data,
           ]);
    }

    public function add(){
        $data = Category::all();
        return view("articles.add",[
            "categories" => $data
        ]);
    }

    public function create(){
        $validator = validator(request()->all() ,
        [
           'title' => 'required',
           'body' => 'required',
           'category_id' => 'required',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $article = new Article;
        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;
        $article->user_id = request()->user_id;
        $article->save();
        return redirect("/articles");
    }

    public function update($id){
        $validator = validator(request()->all() ,
        [
           'title' => 'required',
           'body' => 'required',
           'category_id' => 'required',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $article = Article::find($id);
        $article->title = request()->title;
        $article->body = request()->body;
        $article->category_id = request()->category_id;
        $article->save();
        return redirect("/articles/detail/$id");
    }
    }


