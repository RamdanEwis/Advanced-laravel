<!-- Form to restore a backup -->
<form action="{{ route('backups.restore') }}" method="POST">
    @csrf
    <select name="backup_name">
        @foreach ($backups as $backup)
            <option value="{{ $backup['file_name'] }}">{{ $backup['file_name'] }}</option>
        @endforeach
    </select>
    <button type="submit">Restore Backup</button>
</form>
