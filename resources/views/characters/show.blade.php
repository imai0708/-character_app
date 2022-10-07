<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-8 py-4 bg-white shadow-md">
        <x-flash-message :message="session('notice')" />
        <article class="mb-2">
            <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                {{ $character->title }}</h2>
            <h3>{{ $character->user->name }}</h3>
            <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                現在時刻: <span class="text-red-400 font-bold">{{ date('Y-m-d H:i:s') }}</span>
                記事作成日: {{ $character->created_at }}
            </p>
            <img src="{{ Storage::url($character->image_path) }}" alt="" class="mb-4">
            <p class="text-gray-700 text-base">{!! nl2br(e($character->description)) !!}</p>
        </article>

    </div>
    
<div class="flex flex-row text-center my-4">
    @can('update', $character)
    <a href="{{ route('characters.edit', $character) }}" @endcan
        @can('delete', $character)
        class="bg-purple-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
    <form action="{{ route('characters.destroy', $character) }}" method="post">
        @csrf
        @method('DELETE')
        <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20">
    </form>
@endcan
        </div>

        @auth
            <hr class="my-4">

            <div class="flex justify-end">
                <a href="{{ route('characters.comments.create', $character) }}"
                    class="bg-indigo-400 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline block">コメント登録</a>
            </div>
        @endauth

        <section class="font-sans break-normal text-gray-900 ">
            @foreach ($comments as $comment)
                <div class="my-2">
                    <span class="font-bold mr-3">{{ $comment->user->name }}</span>
                    <span class="text-sm">{{ $comment->created_at }}</span>
                    <p>{!! nl2br(e($comment->body)) !!}</p>
                </div>
                <hr>
            @endforeach
        </section>
</div>
</x-app-layout>
