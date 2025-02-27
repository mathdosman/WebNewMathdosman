<?php

namespace App\Http\Controllers\Member;

use DOMDocument;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->search;
        $data = Post::where('user_id', $user->id)->where(function($query) use($search){
            if($search){
                $query->where('title','like',"%{$search}%")->orWhere('content','like',"%{$search}%");
            }
        })->orderBy('id','desc')->paginate(10);
        return view('member.blogs.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            // 'description'=>'required',
            'content'=>'required',
            'thumbnail'=>'image|mimes:jpeg,jpg,png|max:10240'
        ],[
            'title.required'=>'Judul wajib diisi',
            'content.required'=>'Konten wajib diisi',
            'thumbnail.image'=>'Hanya gambar yang diperbolehkan',
            'thumbnail.max'=>'Maksimum gambar adalah 10MB',
            'thumbnail.mimes'=>'Ekstensi gambar wajib jpeg,jpg atau png',
        ]);

        if($request->hasFile('thumbnail')){
            $gambar = $request->file('thumbnail');
            $gambar_name = time()."_".$gambar->getClientOriginalName();
            $destination_path = public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'));
            $gambar->move($destination_path, $gambar_name);
        }

        $content = $request->content;

        $dom = new DOMDocument();
        $dom->loadHTML($content,9);

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $key => $img) {
            $data = base64_decode(explode(',',explode(';',$img->getAttribute('src'))[1])[1]);
            $image_name = "/upload/" . time(). $key.'.png';
            file_put_contents(public_path().$image_name,$data);

            $img->removeAttribute('src');
            $img->setAttribute('src',$image_name);
        }
        $content = $dom->saveHTML();

        Post::create([
            'title'=> $request->title,
            'description'=> $request->description,
            'content'=> $content,
            'status'=> $request->status,
            'thumbnail'=>isset($gambar_name)?$gambar_name : null,
            'slug'=> $this->generateSlug($request->title),
            'user_id' => Auth::user()->id
        ]);

        // $data=[
        //     'title'=> $request->title,
        //     'description'=> $request->description,
        //     'content'=> $content,
        //     'status'=> $request->status,
        //     'thumbnail'=>isset($image_name)?$image_name : null,
        //     'slug'=> $this->generateSlug($request->title),
        //     'user_id' => Auth::user()->id
        // ];

        // Post::create($data);
        return redirect()->route('member.blogs.index')->with('success','Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('edit',$post);
        $data = $post;
        return view('member.blogs.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'=>'required',
            // 'description'=>'required',
            'content'=>'required',
            'thumbnail'=>'image|mimes:jpeg,jpg,png|max:10240'
        ],[
            'title.required'=>'Judul wajib diisi',
            'content.required'=>'Konten wajib diisi',
            'thumbnail.image'=>'Hanya gambar yang diperbolehkan',
            'thumbnail.max'=>'Maksimum gambar adalah 10MB',
            'thumbnail.mimes'=>'Ekstensi gambar wajib jpeg,jpg atau png',
        ]);

        if($request->hasFile('thumbnail')){
            if(isset($post->thumbnail) && file_exists(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail)){
                unlink(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail);
            }
            $gambar = $request->file('thumbnail');
            $gambar_name = time()."_".$gambar->getClientOriginalName();
            $destination_path = public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'));
            $gambar->move($destination_path, $gambar_name);
        }

        $content = $request->content;

        $dom = new DOMDocument();
        $dom->loadHTML($content,9);

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $key => $img) {

            // Check if the image is a new one
            if (strpos($img->getAttribute('src'),'data:image/') ===0) {

                $data = base64_decode(explode(',',explode(';',$img->getAttribute('src'))[1])[1]);
                $image_name = "/upload/" . time(). $key.'.png';
                file_put_contents(public_path().$image_name,$data);

                $img->removeAttribute('src');
                $img->setAttribute('src',$image_name);
            }

        }
        $content = $dom->saveHTML();

        $data=[
            'title'=> $request->title,
            'description'=> $request->description,
            'content'=> $content,
            'status'=> $request->status,
            'slug'=> $this->generateSlug($request->title, $post->id),
            'thumbnail'=>isset($image_name)?$image_name : $post->thumbnail
        ];

        Post::where('id',$post->id)->update($data);

        return redirect()->route('member.blogs.index')->with('success','Data berhasil di-update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete',$post);
        if(isset($post->thumbnail) && file_exists(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail)){
            unlink(public_path(getenv('CUSTOM_THUMBNAIL_LOCATION'))."/".$post->thumbnail);
        }

        $dom= new DOMDocument();
        $dom->loadHTML($post->content,9);
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $key => $img) {

            $src = $img->getAttribute('src');
            $path = Str::of($src)->after('/');


            if (File::exists($path)) {
                File::delete($path);

            }
        }

        Post::where('id',$post->id)->delete();
        return redirect()->route('member.blogs.index')->with('success','Data berhasil dihapus!');
    }

    private function generateSlug($title, $id=null){
        $slug = Str::slug($title);
        $count = Post::where('slug',$slug)->when($id, function($query,$id){
            return $query->where('id','!=',$id);
        })->count();

        if($count > 0){
            $slug = $slug."-".($count+1);
        }
        return $slug;
    }
}
