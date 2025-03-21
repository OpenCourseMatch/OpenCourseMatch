<style>
    .users-table {
        border-collapse: collapse;
        margin: 0;
        padding: 0;
        width: 100%;
        font-size: .8em;
    }

    .users-table th,
    .users-table td {
        padding: .5em;
    }

    .users-table th {
        color: #f5fff9;
        background-color: #00a34c;
        border: 1px solid #00a34c;
        font-weight: normal;
    }

    .users-table td {
        border: 1px solid #999999;
        padding: 0.5em;
    }

    .users-table tr:nth-child(even) {
        background-color: #eeeeee;
    }
</style>

@if(count($assignment["courseLeaders"]) > 0)
    <h3>
        {{ t("Course leaders") }}
    </h3>

    <table class="users-table">
        <thead>
            <tr>
                <th colspan="4">
                    @if($assignment["course"] instanceof Course)
                        {{ $assignment["course"]->getTitle() }}
                    @else
                        {{ t("Not assigned to any course") }}
                    @endif
                </th>
            </tr>
            <tr>
                <th>
                    {{ t("First name") }}
                </th>
                <th>
                    {{ t("Last name") }}
                </th>
                <th>
                    {{ t("Group") }}
                </th>
                <th>
                    {{ t("Username") }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignment["courseLeaders"] as $courseLeader)
                <tr>
                    <td>
                        {{ $courseLeader->getFirstName() }}
                    </td>
                    <td>
                        {{ $courseLeader->getLastName() }}
                    </td>
                    <td>
                        {{ $courseLeader->getGroup()?->getName() ?? t("Default group") }}
                    </td>
                    <td>
                        {{ $courseLeader->getUsername() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<h3>
    {{ t("Participants") }}
</h3>

@if(count($assignment["participants"]) === 0)
    <p>
        {{ t("No participants were assigned to this course.") }}
    </p>
@else
    <table class="users-table">
        <thead>
            <tr>
                <th colspan="4">
                    @if($assignment["course"] instanceof Course)
                        {{ $assignment["course"]->getTitle() }}
                    @else
                        {{ t("Not assigned to any course") }}
                    @endif
                </th>
            </tr>
            <tr>
                <th>
                    {{ t("First name") }}
                </th>
                <th>
                    {{ t("Last name") }}
                </th>
                <th>
                    {{ t("Group") }}
                </th>
                <th>
                    {{ t("Username") }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignment["participants"] as $courseLeader)
                <tr>
                    <td>
                        {{ $courseLeader->getFirstName() }}
                    </td>
                    <td>
                        {{ $courseLeader->getLastName() }}
                    </td>
                    <td>
                        {{ $courseLeader->getGroup()?->getName() ?? t("Default group") }}
                    </td>
                    <td>
                        {{ $courseLeader->getUsername() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
