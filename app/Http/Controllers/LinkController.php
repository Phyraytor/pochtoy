<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $links = Link::latest()->take(10)->get();
    return response()->json($links);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $request->validate([
      'link' => 'required',
    ]);
    $validation = filter_var($request['link'], FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    if (!$validation) {
      return response('Bad link', 403);
    }

    $lastLink = Link::latest()->first();
    $shortLink = 'a';
    if ($lastLink) {
      $shortLink = $lastLink['shortLink'];
      $shortLink++;
    }
    Link::create(['link' => $request['link'], 'shortLink' => $shortLink]);
    return response('success', 201);
  }

  /**
   * Display the specified resource.
   *
   * @param  string $link
   * @return \Illuminate\Http\Response
   */
  public function show(string $link) {
    $link = Link::where('shortLink', $link)->first();
    if ($link['link']) {
      return redirect()->away($link['link']);
    } else {
      return response('Not found', 404);
    }
  }
}
