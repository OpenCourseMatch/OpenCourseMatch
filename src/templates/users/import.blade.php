@component("components.layout.appshell", [
    "title" => t("Participants and tutors"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Import users") }}
    </h1>

    <p>
        {{ t("You can create accounts for a list of users all at once by importing the data from a CSV file.") }}
        {{ t("It has to follow this format:") }}
    </p>
    <div class="flex justify-center my-2">
        <table>
            <thead class="bg-primary text-primary-font">
                <tr>
                    <th class="p-2 border border-primary">A</th>
                    <th class="p-2 border border-primary">B</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border border-gray">{{ t("Last name") }}</td>
                    <td class="p-2 border border-gray">{{ t("First name") }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <p>
        {{ t("The columns must be separated by a comma, and the first line is not ignored.") }}
        {{ t("The import is limited to 50 rows at a time.") }}
    </p>

    <form method="post" action="{{ Router::generate("users-import-action") }}" data-redirect="{{ Router::generate("users-overview") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="file" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Choose file") }}
            </label>
            <input id="file"
                   name="file"
                   type="file"
                   class="{{ TailwindUtil::$input }}"
                   placeholder="{{ t("Choose file") }}"
                   accept="text/csv"
                   required>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="group" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Group") }}
            </label>
            <select id="group"
                    name="group"
                    class="{{ TailwindUtil::$input }}">
                <option value="">{{ t("Default group") }}</option>
                @foreach($groups as $group)
                    <option value="{{ $group->getId() }}">
                        {{ $group->getName() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="password" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Password") }} ({{ t("Leave empty to generate automatically") }})
            </label>
            <input id="password"
                   name="password"
                   type="password"
                   class="{{ TailwindUtil::$input }}"
                   value=""
                   minlength="8"
                   maxlength="256"
                   placeholder="{{ t("Password") }}">
        </div>

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("components.icons.buttonload")
            @include("components.icons.import")
            {{ t("Import") }}
        </button>
    </form>

    @include("components.modals.defaultabort")
    <script type="module">
        import * as UsersImport from "{{ Router::staticFilePath("js/users/import.js") }}";
        UsersImport.init();
    </script>
@endcomponent
