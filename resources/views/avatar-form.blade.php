<x-layout>
    <div class="container container-narrow py-md-5">
        <form action="manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar">
                @error('avatar')
                    <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</x-layout>
