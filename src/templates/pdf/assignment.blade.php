@component("shells.pdf")
    @foreach($assignments as $i => $assignment)
        @if($assignment["course"] instanceof \app\courses\Course)
            <h2>
                {{ $assignment["course"]->getTitle() }}
            </h2>
        @else
            <h2>
                {{ t("Not assigned to any course") }}
            </h2>
        @endif

        @include("ui.pdf.assignment", [
            "assignment" => $assignment
        ])

        @if($i < count($assignments) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
@endcomponent
