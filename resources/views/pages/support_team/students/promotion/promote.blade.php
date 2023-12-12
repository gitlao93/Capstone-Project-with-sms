<form method="post" action="{{ route('students.promote', [$fc, $fs, $tc, $ts]) }}">
    @csrf
    <div class="bulk-action row">
        <select class="form-control col-md-9" name="bulk_action" id="bulk_action">
            <option value="P">Promote</option>
            <option value="D">Don't Promote</option>
            <option value="G">Graduated</option>
        </select>
        <div class="col-md-3">
            <div class="row">
                {{-- <button type="button" class="btn btn-primary col-md-4 mx-2" id="bulk_btn"><i class="icon-stairs-up mr-2"></i> Bulk Action</button> --}}
                <button class="btn btn-success col mx-2"><i class="icon-stairs-up mr-2"></i> Apply Changes</button>
            </div>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>Photo</th>
            <th>Name</th>
            <th>Current Session</th>
            <th hidden>Action</th>
        <tbody>
        @foreach($students->sortBy('user.name') as $sr)
            <tr>
                <td><input type="checkbox" class="student-checkbox" name="c-{{$sr->id}}"></td>
                <td><img class="rounded-circle" style="height: 30px; width: 30px;" src="{{ $sr->user->photo }}" alt="img"></td>
                <td>{{ $sr->user->name }}</td>
                <td>{{ $sr->session }}</td>
                <td hidden>
                    <select class="form-control select" name="p-{{$sr->id}}" id="p-{{$sr->id}}">
                        <option value="P">Promote</option>
                        <option value="D">Don't Promote</option>
                        <option value="G">Graduated</option>
                    </select>
                </td>
        @endforeach
        </tbody>
    </table>
</form>

<script>
    document.getElementById('select-all').addEventListener('click', function(event) {
        var checkboxes = document.querySelectorAll('.student-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = event.target.checked;
        }
    });
</script>