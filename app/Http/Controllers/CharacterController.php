<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Comments;
use App\Http\Requests\CharacterRequest;
use Illuminate\Support\Facades\Auth;

class characterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $characters = Character::with('user')->latest()->Paginate(4);
        return view('characters.index', compact('characters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('characters.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CharacterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CharacterRequest $request)
    {
        $character = new Character($request->all());
        $character->user_id = $request->user()->id;

        $file = $request->file('image');
        $character->image = self::createFileName($file);
        // dd($character->image);

        DB::beginTransaction();
        try {
            $character->save();
            if (!Storage::putFileAs('images/posts', $file, $character->image)) {
                throw new \Exception('画像ファイルの保存に失敗しました。');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('characters.show', $character)
            ->with('notice', '記事を登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $character = Character::find($id);
        $character = Character::with('user')->find($id);
        $comments = $character->comments()->latest()->get()->load(['user']);

        // if (Auth::user()) {
        //     return view('chracters.show', compact('character', 'comments'));
        // } else {
        //     return view('characters.show', compact('character'));
        return view('characters.show', compact('character', 'comments'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $character = Character::find($id);
        return view('character.edit', compact('character', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CharacterRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CharacterRequest $request, $id)
    {
        $character = Character::find($id);
        $character->fill($request->all());

        if ($request->user()->cannot('update', $character)) {
            return redirect()->route('posts.show', $character)
                ->withErrors('自分の記事以外は更新できません');
        }

        $file = $request->file('image');
        if ($file) {
            $delete_file_path = $character->image_path;
            $character->image = self::createFileName($file);
        }

        DB::beginTransaction();
        try {
            $character->save();
            if ($file) {
                if (!Storage::putFileAs('images/characters', $file, $character->image)) {
                    throw new \Exception('画像ファイルの保存に失敗しました。');
                }
                if (!Storage::delete($delete_file_path)) {
                    throw new \Exception('画像ファイルの削除に失敗しました。');
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('character.show', $character)
            ->with('notice', '記事を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $character = Character::find($id);

        DB::beginTransaction();
        try {
            $character->delete();
            if (!Storage::delete($character->image_path)) {
                throw new \Exception('画像ファイルの削除に失敗しました。');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('characters.index')
            ->with('notice', '記事を削除しました');
    }
    public static function createFileName($file)
    {
        return date('YmdHis') . '_' . $file->getClientOriginalName();
    }
}
