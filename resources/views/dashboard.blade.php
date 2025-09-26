<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <x-button class="float-right m-2" type="button" href="{{ route('articles.create') }}" variant="primary">
                    Add Article
                </x-button>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles  as $article)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$article->title??''}}</td>
                                <td>{{$article->description??''}}</td>
                                <td>{{$article->status?'Active':'Inactive'}}</td>
                                <td>
                                    <x-button
                                    type="button"
                                    href="{{ route('articles.show',[$article->id]) }}"
                                    variant="success"
                                    >
                                    View
                                    </x-button>
                                    <x-button
                                    type="button"
                                    href="{{ route('articles.edit',[$article->id]) }}"
                                    variant="primary"
                                    >
                                    Edit
                                    </x-button>
                                    <form action="{{ route('articles.destroy',[$article->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button
                                        type="button"
                                        variant="danger"
                                        >
                                        Delete
                                        </x-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>