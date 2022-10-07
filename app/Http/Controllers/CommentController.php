<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Character;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\DB;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Character $character)
    {
        return view('comments.create', compact('character'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @return \Illuminate\Http\Response
     */
public function store(CommentReques $request)    {
        $comment = new Comment($request->all());
        $comment->user_id = $request->user()->id;

        // $file = $request->file('image');
        // $comment->image = self::createFileName($file);

        // トランザクション開始
        DB::beginTransaction();
        try {
            // 登録
            $character->comments()->save($comment);

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('characters.show', $character)
            ->with('notice', 'コメントを登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\Character  $character
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Character $character, Comment $comment)
    {
        return view('comments.edit', compact('character', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @param  \App\Models\Characters  $character
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
public function update(CommentRequest $request, Comment $comment)    {
        if ($request->user()->cannot('update', $comment)) {
            return redirect()->route('characters.show', $character)
                ->withErrors('自分のコメント以外は更新できません');
        }

        $comment->fill($request->all());

        // トランザクション開始
        DB::beginTransaction();
        try {
            // 更新
            $comment->save();

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('character.show', $character)
            ->with('notice', 'コメントを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Character  $character
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Character $character, Comment $comment)
    {
        // トランザクション開始
        DB::beginTransaction();
        try {
            $comment->delete();

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('characters.show', $character)
            ->with('notice', 'コメントを削除しました');
    }
}
