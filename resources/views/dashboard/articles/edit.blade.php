<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('articles.update',[$article]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" :value="__('Title')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="title" :value="old('title',$article->title)" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description',$article->description)" required />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="category" :value="__('Category')" />
                        <x-select 
                            id="category"
                            class="block mt-1 w-full "
                            name="category_id" 
                            :options="$categories" 
                            selected="$article->category_id"
                        />

                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="tag" :value="__('Tags')" />
                        <x-select 
                            id="tag"
                            class="block mt-1 w-full "
                            name="tag_id[]" 
                            :options="$tags" 
                            selected="$article->tags->pluck('id')->toArray()"
                            customattributes="multiple"
                        />

                        <x-input-error :messages="$errors->get('tag_id')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Image')" />
                        <img height="200px" width="200px" src="{{ asset('storage/website/'.$article->image) }}">
                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="file"
                                        name="image"
                                        accept=".jpg, .jpeg, .png"
                                         />

                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">

                        <x-button class="ms-4 m-2" type="submit" variant="primary">
                            {{ __('Update') }}
                        </x--button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>