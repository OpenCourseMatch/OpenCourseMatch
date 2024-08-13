<dialog id="modal"
        class="p-0 w-[90vw] max-w-[960px] bg-gray-light border-none rounded text-font">
    <div class="flex items-center justify-between w-full gap-4 p-4 border-b border-b-gray">
        <h2 class="m-0">
            <span id="modal-content-title"></span>
        </h2>
        <div class="">
            <button class="{{ TailwindUtil::button() }} modal-abort-button">
                <span class="modal-content-abort">{{ t("Abort") }}</span>
            </button>
        </div>
    </div>
    <div class="grow w-full p-4" id="modal-content-body">
    </div>
    <div class="flex items-center justify-end w-full gap-4 p-4 border-t border-t-gray">
        <div class="">
            <button class="{{ TailwindUtil::button(false, "secondary") }} modal-confirm-button">
                <span class="modal-content-confirm">{{ t("Confirm") }}</span>
            </button>
        </div>
        <div class="">
            <button class="{{ TailwindUtil::button() }} modal-abort-button">
                <span class="modal-content-abort">{{ t("Abort") }}</span>
            </button>
        </div>
    </div>
</dialog>
