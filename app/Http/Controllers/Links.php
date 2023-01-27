<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class Links extends Controller
{
    function index(Request $request){
        $links = QueryBuilder::for (Link::class)
            ->allowedFilters(['full_link', 'short_link'])
            ->allowedSorts('full_link', 'short_link', 'views', "id")
            ->where("user_id", Auth::user()->id)
            ->paginate($request->get('perPage', 5));
        return response()->json($links);
    }

    function show($id){
        $link = Link::find($id);
        return response()->json($link);
    }

    function store(StoreLinkRequest $request){
        $link = new Link([
            "short_link" => $request->short_link,
            "full_link" => $request->full_link,
            "user_id" => Auth::user()->id,
            "views" => 0
        ]);
        $link->save();
        return response()->json($link, 201);
    }

    function update($id, UpdateLinkRequest $request){
        $link = Link::find($id);
        $link->full_link = $request->full_link;
        $link->short_link = $request->short_link;
        $link->save();
        return response()->json($link);
    }

    function destroy($id){
        $link = Link::find($id);
        $link->delete();
        return response(null, 204);
    }
}