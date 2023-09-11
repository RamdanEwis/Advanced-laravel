
<!-- Button to create a new bac                          k    up -->
<form action="{{ route('backups.create2') }}" method="POST">
    @csrf
    <button type="submit">Create Backup</button>
</form>
@foreach ($backups as $backup)
    <div>

        <p>id: {{ $backup['id'] }}</p>
        <p>name: {{ $backup['name'] }}</p>
        <p>size: {{ $backup['size'] }}</p>
        <form action="{{ route('backups.delete2', $backup['name']) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach




