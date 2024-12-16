@component("components.layout.appshell")
    <h1 class="mb-2">
        {{ t("Course assignment algorithm") }}
    </h1>
    <p>
        {{ t("The course assignment algorithm has been started.") }}
    </p>
    <p>
        {{ t("You will be redirected automatically when the algorithm execution has finished.") }}
        {{ t("You may also leave this page.") }}
    </p>

    <script type="module">
        import * as AssignmentAlgorithm from "{{ Router::staticFilePath("js/assignment/algo.js") }}";
        AssignmentAlgorithm.init(
            "{{ Router::generate("course-assignment-status") }}",
            "{{ Router::generate("course-assignment-redirect") }}"
        );
    </script>
@endcomponent
