@extends('layouts.master')
@section('page_title', 'Student Information - '.$my_class->name)
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Students List</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-students" class="nav-link active" data-toggle="tab">All {{ $my_class->name }} Students</a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Sections </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($sections as $s)
                    <a href="#s{{ $s->id }}" class="dropdown-item" data-toggle="tab">{{ $my_class->name.' '.$s->name }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-students">
                <button type="button" class="btn btn-primary" id="assign-section-btn">Assign Section</button>
                <form id="assign-section-form" style="display: none;">
                    <select class="form-control mb-2" id="section-dropdown">
                        @foreach($sections as $s)
                        <option value="{{ $s->id }}">{{ $my_class->name.' '.$s->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-success mr-2" id="assign-btn">Assign</button>
                    <button type="button" class="btn btn-danger" id="cancel-btn">Cancel</button>
                </form>
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th class="student-checkbox-cell" style="display: none;"></th>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM_No</th>
                            <th>Section</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $s)
                        <tr>
                            <td class="student-checkbox-cell" style="display: none;"> @if(!$s->section) <!-- Check if section is not assigned -->
                                <input type="checkbox" class="student-checkbox" value="{{ $s->id }}">
                                @else
                                <input type="checkbox" class="student-checkbox" value="{{ $s->id }}" disabled>
                                @endif
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ $s->user->photo }}" alt="photo"></td>
                            <td>{{ $s->user->name }}</td>
                            <td>{{ $s->adm_no }}</td>
                            <td>{{ $my_class->name.' ' . ($s->section->name ?? '') }}</td>
                            <td>{{ $s->user->email }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="dropdown-item"><i class="icon-eye"></i> View Profile</a>
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('students.edit', Qs::hash($s->id)) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                            <a href="{{ route('st.reset_pass', Qs::hash($s->user->id)) }}" class="dropdown-item"><i class="icon-lock"></i> Reset password</a>
                                            @endif
                                            <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" class="dropdown-item"><i class="icon-check"></i> Marksheet</a>

                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ Qs::hash($s->user->id) }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($s->user->id) }}" action="{{ route('students.destroy', Qs::hash($s->user->id)) }}" class="hidden">@csrf @method('delete')</form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @foreach($sections as $se)
            <div class="tab-pane fade" id="s{{$se->id}}">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>

                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM_No</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students->where('section_id', $se->id) as $sr)
                        <tr>

                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ $sr->user->photo }}" alt="photo"></td>
                            <td>{{ $sr->user->name }}</td>
                            <td>{{ $sr->adm_no }}</td>
                            <td>{{ $sr->user->email }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('students.show', Qs::hash($sr->id)) }}" class="dropdown-item"><i class="icon-eye"></i> View Info</a>
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('students.edit', Qs::hash($sr->id)) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                            <a href="{{ route('st.reset_pass', Qs::hash($sr->user->id)) }}" class="dropdown-item"><i class="icon-lock"></i> Reset password</a>
                                            @endif
                                            <a href="#" class="dropdown-item"><i class="icon-check"></i> Marksheet</a>

                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ Qs::hash($sr->user->id) }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($sr->user->id) }}" action="{{ route('students.destroy', Qs::hash($sr->user->id)) }}" class="hidden">@csrf @method('delete')</form>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @endforeach

        </div>
    </div>
</div>

{{--Student List Ends--}}
<script>
    $(document).ready(function() {
        $('#assign-section-btn').click(function() {
            // Show/hide elements when the button is clicked
            $('.student-checkbox-cell').each(function() {
                let sectionValue = $(this).closest('tr').find('.section-value').text().trim();
                if (!sectionValue) {
                    $(this).toggle();
                } else {
                    $(this).hide();
                }
            });
            $('#assign-section-form').toggle();
            $(this).hide(); // Hide the "Assign Section" button
        });

        $('#cancel-btn').click(function() {
            // Show the "Assign Section" button and hide the form
            $('#assign-section-btn').show();
            $('#assign-section-form').hide();
            $('.student-checkbox-cell').hide(); // Hide checkboxes on cancel
        });



        // When the form is submitted
        $('#assign-section-form').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get the selected section ID and the student IDs
            let sectionId = $('#section-dropdown').val();
            let studentIds = [];
          
            $('.student-checkbox:checked').each(function() {
                studentIds.push($(this).val());
            });
           
            // Make an AJAX request to the server to assign sections
            console.log(sectionId)
            $.ajax({
                type: 'POST',
                url: '{{ route("students.assign") }}',
                data: {
                    section_id: sectionId,
                    student_ids: studentIds
                },
              
                success: function(response) {
                    // Handle success, maybe show a success message or reload the page
                    location.reload(); // For example, reload the page after successful assignment
                },
                error: function(error) {
                    // Handle error if the assignment fails
                    console.error('Assignment failed:', error);
                    // You can show an error message to the user or handle the error accordingly
                }
            });
        });



    });
</script>

@endsection