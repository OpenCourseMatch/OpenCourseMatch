<dialog id="modal"
        class="m-auto p-0 w-[90vw] max-w-[960px] bg-surface-100 text-surface-900 shadow rounded">
    <div class="flex items-center justify-between w-full gap-4 p-4 border-b border-b-surface-200">
        <h2 class="m-0">
            <span id="modal-content-title"></span>
        </h2>
        <div class="">
            <button class="{{ TailwindUtil::button(false, "surface") }} modal-abort-button">
                <span class="modal-content-abort">{{ t("Abort") }}</span>
            </button>
        </div>
    </div>
    <div class="grow w-full p-4" id="modal-content-body">
    </div>
    <div class="flex items-center justify-end w-full gap-4 p-4 border-t border-t-surface-200">
        <div class="">
            <button class="{{ TailwindUtil::button(false, "danger") }} modal-confirm-button">
                <span class="modal-content-confirm">{{ t("Confirm") }}</span>
            </button>
        </div>
        <div class="">
            <button class="{{ TailwindUtil::button(false, "surface") }} modal-abort-button">
                <span class="modal-content-abort">{{ t("Abort") }}</span>
            </button>
        </div>
    </div>
</dialog>
