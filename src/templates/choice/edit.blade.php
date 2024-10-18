@component("components.layout.appshell", [
    "title" => t("Choose courses"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Choose courses") }}
    </h1>

    <form method="post" action="{{ Router::generate("choice-save") }}">
        @for($i = 0; $i < $voteCount; $i++)
            <input type="hidden" name="choice[]" value="" data-choice-index="{{ $i }}">
            <div class="[&:not([data-active])]:hidden mb-2"
                 data-choice-index="{{ $i }}"
                 @if($i === 0) data-active @endif>
                <h2 class="mb-2">
                    {{ t("Choice") }} {{ $i + 1 }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-2">
                    @foreach($choosableCourses as $course)
                        @include("components.choice", [
                            "course" => $course,
                            "choice" => $i
                        ])
                    @endforeach
                </div>

                <div class="flex w-full justify-between">
                    @if($i > 0)
                        <button type="button"
                                class="{{ TailwindUtil::button(true) }}"
                                data-action="back">
                            {{ t("Back") }}
                        </button>
                    @else
                        <span></span>
                    @endif
                    @if($i < $voteCount - 1)
                        <button type="button"
                                class="{{ TailwindUtil::button(true) }}"
                                data-action="next">
                            {{ t("Next") }}
                        </button>
                    @else
                        <span></span>
                    @endif
                </div>
            </div>
        @endfor

        <button type="submit"
                class="{{ TailwindUtil::button() }} w-full"
                disabled>
            {{ t("Save selection") }}
        </button>
    </form>

    <script type="module">
        import * as Choice from "{{ Router::staticFilePath("js/choice/choice.js") }}";
        Choice.init();
    </script>
@endcomponent
