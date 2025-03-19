@component("pdf.components.pdfshell")
    @foreach($assignments as $i => $assignment)
        @if($assignment["course"] instanceof Course)
            <h2>
                {{ $assignment["course"]->getTitle() }}
            </h2>
        @else
            <h2>
                {{ t("Not assigned to any course") }}
            </h2>
        @endif

        @include("pdf.components.assignment", [
            "assignment" => $assignment
        ])

        @if($i < count($assignments) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
@endcomponent
