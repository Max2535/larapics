<x-layout title="Discover free images">

    <div class="container-fluid mt-4">
        <a href="{{ route('images.create') }}">Upload Image</a>
        @if ($message = session('message'))
            <x-alert type="success" dismissible>
                {{ $component->icon() }}
                {{ $message }}
            </x-alert>
        @endif
        <div class="row" data-masonry='{"percentPosition": true }'>
            @foreach ($images as $image)
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="card">
                        <a href="{{ $image->permalink() }}">
                            <img src="{{ $image->fileUrl() }}" alt="{{ $image->title }}" class="card-img-top" />
                        </a>
                        <div class="photo-buttons">
                            <div>
                                <a href="{{ $image->route('edit') }}" class="btn btn-sm btn-info me-2">Edit</a>
                                <x-form action="{{ $image->route('destroy') }}" method="DELETE">
                                    <button class="btn btn-sm btn-danger" type="submit"
                                        onclick="confirm('Are you sure?')">Delete</button>
                                </x-form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $images->links() }}
    </div>
</x-layout>
