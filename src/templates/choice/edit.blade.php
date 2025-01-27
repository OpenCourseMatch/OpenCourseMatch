@component("components.layout.appshell", [
    "title" => t("Choose courses"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Choose courses") }}
    </h1>

    <form method="post" action="{{ $saveLink }}">
        @for($i = 0; $i < $voteCount; $i++)
            <input type="hidden" name="choice[]"
                   value="{{ $user->getChoice($i)?->getCourseId() ?? "" }}"
                   data-choice-index="{{ $i }}">
            <div class="choice-container [&:not([data-active])]:hidden mb-2"
                 data-choice-index="{{ $i }}"
                 @if($i === 0) data-active @endif>
                <h2 class="mb-2">
                    {{ t("Choice \$\$index\$\$", ["index" => $i + 1]) }}
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
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px">
                                <path xmlns="http://www.w3.org/2000/svg" d="m142-480 294 294q15 15 14.5 35T435-116q-15 15-35 15t-35-15L57-423q-12-12-18-27t-6-30q0-15 6-30t18-27l308-308q15-15 35.5-14.5T436-844q15 15 15 35t-15 35L142-480Z"/>
                            </svg>
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
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px">
                                <path d="M579-480 285-774q-15-15-14.5-35.5T286-845q15-15 35.5-15t35.5 15l307 308q12 12 18 27t6 30q0 15-6 30t-18 27L356-115q-15 15-35 14.5T286-116q-15-15-15-35.5t15-35.5l293-293Z"/>
                            </svg>
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
            @include("components.icons.buttonload")
            {{ t("Save choice") }}
        </button>
    </form>

    <script type="module">
        import * as Choice from "{{ Router::staticFilePath("js/choice/choice.js") }}";
        Choice.init();
    </script>
@endcomponent
