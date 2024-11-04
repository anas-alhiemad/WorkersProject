<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoringPostRequest;
use App\Services\PostService\StoringPostService;
class PostController extends Controller
{
    public function store(StoringPostRequest $request){


        return (new StoringPostService())->store($request);

    //         try {
    //             DB::beginTransaction();
    //             $data = $request ->except('photo');
    //             $data['worker_id'] = auth() ->gurd('worker') ->id();
    //             $post = Post::create($data);
    //             if ($request -> hasfile('photos')) {
    //                 foreach ($request -> file('photos') as $photo) {
    //                    $postPhotos = new PostPhotos();
    //                    $postPhotos -> postId = $post -> id;
    //                    $postPhotos -> photo = $photo -> store('photos');
    //                    $postPhotos -> save();
    //                 }
    //             }

    //             DB::commit();
    //             return response()->json(["Message" => "post has been created succesfuly"]);
    //         } catch (Exception $e) {
    //             DB::rollback();
    //             return $e->getMessage();
    //         }
     }
}
