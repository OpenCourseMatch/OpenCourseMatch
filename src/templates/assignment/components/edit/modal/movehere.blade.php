<div>
    {{-- General course information --}}
    <div class="flex flex-col sm:flex-row flex-wrap items-center gap-x-4 gap-y-2 mb-2">
        <h3>
            {{ $course->getTitle() }}
        </h3>
    </div>

    {{-- User table --}}
    <table id="movehere-users-table" class="stripe">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th>{{ t("First name") }}</th>
                <th>{{ t("Last name") }}</th>
                <th>{{ t("Group") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $account)
                <tr>
                    <td>{{ $account->getId() }}</td>
                    <td>
                        <span class="flex gap-2">
                            @include("components.icons.buttonload")
                            @if($account->getLeadingCourseId() === $course->getId())
                                @include("components.icons.courseleader")
                            @endif
                        </span>
                    </td>
                    <td>
                        {{ $account->getFirstName() }}
                    </td>
                    <td>
                        {{ $account->getLastName() }}
                    </td>
                    <td>
                        @if($account->getGroup() !== null)
                            {{ $account->getGroup()->getName() }}
                        @else
                            {{ t("Default group") }}
                       @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script type="module">
        import * as EditCourseAssignment from "{{ Router::staticFilePath("js/assignment/edit.js") }}";
        EditCourseAssignment.initMoveHereModal({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        }, "{{ Router::generate("course-assignment-edit-move-here", [
            "course" => $course->getId()
        ]) }}");
    </script>
</div>
