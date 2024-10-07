@component("components.layout.appshell", [
    "title" => t("Choose courses"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1>
        {{ t("Choose courses") }}
    </h1>

    <form>
        @for($i = 0; $i < $voteCount; $i++)
            <div id="choice-{{ $i }}" class="">
                <h2>
                    {{ t("Choice") }} {{ $i + 1 }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($choosableCourses as $course)
                        @include("components.choice", [
                            "course" => $course,
                            "choice" => $i
                        ])
                    @endforeach
                </div>
            </div>
        @endfor
    </form>
@endcomponent
