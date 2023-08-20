@extends('master')

@section('title', 'Content CRUD')

@section('content')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<div class="d-flex justify-content-end mb-3">
    <div class="dropdown">
        <button class="btn btn-sm btn-custom dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-user"></span> {{ Auth::user()->name }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="{{ url('http://127.0.0.1:8000/login') }}">Logout</a>
        </div>
    </div>
</div>


<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" href="{{ url('content') }}">Content List</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('content/create') }}">Create Content</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('http://127.0.0.1:8000/') }}">Main Menu</a>
    </li>
</ul>

<div class="container mt-5">
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="https://www.w3schools.com/howto/img_nature_wide.jpg" alt="New York" width="1200" height="700">
            <div class="carousel-caption">
                <h3>Welcome to My Blog</h3>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-5">
        <table class="table table-bordered" id="tbContent">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Topic</th>
                    <th>Tags</th>
                    <th>Links</th>
                    <th>Create Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contents as $content)
                <tr>
                    <td>{{ $content->id }}</td>
                    <td>
                        <a href="{{ url('#') }}" class="detail-item"
                            data-detail="{{ $content->description }}"
                            data-topic="{{ $content->topic }}">{{ $content->topic }}</a>
                    </td>
                    <td>{{ $content->tags }}</td>
                    <td><a href="{{ $content->links }}" target="_blank">{{ $content->links }}</a></td>
                    <td>{{ $content->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $content->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $content->status ? 'Published' : 'Unpublished' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ url("content/{$content->id}/edit") }}" role="button"
                            class="btn btn-sm btn-warning">Edit</a>
                        <button type="button"
                            class="btn btn-sm {{ $content->status ? 'btn-danger' : 'btn-success' }} delete-item"
                            data-id="{{ $content->id }}">
                            {{ $content->status ? 'Disable' : 'Enable' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $contents->links() }}
</div>
@endsection

@push('script')
<script>
    document.querySelector('#tbContent').addEventListener('click', (e) => {
        if (e.target.matches('.delete-item')) {
            const contentId = e.target.dataset.id;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete($url + '/content/' + contentId).then((response) => {
                        Swal.fire('Success!', 'Your status has been edited.', 'success');
                        setTimeout(() => {
                            window.location.href = $url + '/content';
                        }, 2000);
                    });
                }
            });
        } else if (e.target.matches('.detail-item')) {
            const topic = e.target.dataset.topic;
            const detail = e.target.dataset.detail;
            Swal.fire({
                title: '[' + topic + ']',
                html: detail,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Close'
            });
        }
    });
</script>
@endpush
