@props(['action'])

<div>
    <form action="{{ $action }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="button" class="btn-delete ms-2 px-2 py-1 rounded bg-danger text-white border-0">
            Delete
        </button>
    </form>
</div>
