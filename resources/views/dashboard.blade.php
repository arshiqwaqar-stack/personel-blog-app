<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container ">
        <x-button class="float-right m-2" type="button" href="{{ route('articles.create') }}" variant="primary">
            Add Article
        </x-button>
    </div>
    <div class="py-12 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Image</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@push('js')
<script>
    $(document).ready(function () {
        
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('articles.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                // {data: 'id', name: 'id'},
                {data: 'title', name: 'title',orderable: true, searchable: true},
                {data: 'description', name: 'description',orderable: true, searchable: true},
                {data: 'image', name: 'image',orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('.dataTables_filter input').attr('placeholder', 'Search articles...');
        
    });
</script>
@endpush
</x-app-layout>