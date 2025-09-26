<x-app-layout>
<div class="container my-4">
    <div class="card shadow-sm p-3">
        <h3 class="mb-3">{{ $article->title }}</h3>

        <div class="card-body">
            <h5>Description</h5>
            <p class="text-muted">{{ $article->description }}</p>

            @if($article->image)
                <h5>Image</h5>
                <img src="{{ asset('storage/website/' . ($article->image ?? '')) }}" alt="{{ $article->title }}"
                     alt="{{ $article->title }}" 
                     class="img-fluid rounded mb-3" height="200px" width="200px">
            @endif

            <h5>Category</h5>
            <p>{{ $article->category->name ?? 'Uncategorized' }}</p>

            <h5>Status</h5>
            <p>
                <span class="badge {{ $article->status ? 'bg-success' : 'bg-danger' }}">
                    {{ $article->status ? 'Active' : 'Inactive' }}
                </span>
            </p>
        </div>
    </div>
</div>



</x-app-layout>