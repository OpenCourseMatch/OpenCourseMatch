{{-- This component is not used anywhere. --}}
{{-- It is used to ensure that certain classes, e.g. those from TailwindUtil, are always present in the final CSS: --}}
<div class="
    w-full h-full md:w-full md:h-full
    translate-x-full md:translate-x-full
    flex inline-flex justify-around items-center border rounded
    text-sm font-bold
    data-[required]:after:content-['*'] data-[required]:after:text-primary-500
    placeholder:text-surface-500

    {{-- Custom project safelist --}}
    dt-container dt-layout-row dt-search dt-paging dataTable
    dt-paging-button current disabled previous next
"></div>
