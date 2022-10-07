<x-app-layout>

    <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
        <x-flash-message :message="session('notice')" />
        <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">
            @foreach ($characters as $character)
                <article class="w-full px-4 md:w-1/2 text-xl text-gray-800 leading-normal">
                    <a href="{{ route('characters.show', $character) }}">
                        <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                            {{ $character->title }}</h2>
                        <h3>{{ $character->user->name }}</h3>
                        <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                            現在時刻: <span class="text-red-400 font-bold">{{ date('Y-m-d H:i:s') }}</span>
                            記事作成日: {{ $character->created_at }}
                        </p>
                        <img class="w-full mb-2" src="{{ Storage::url($character->image_path) }}" alt="">
                        <p class="text-gray-700 text-base">{{ Str::limit($character->description, 50) }}</p>
                </article>
            @endforeach
        </div>
        {{ $characters->links() }}
    </div>
</x-app-layout>
