{{-- Sidebar popup --}}
<aside class="flex flex-col fixed z-200 top-16 left-0
              h-[calc(100%-calc(var(--spacing)*16))] w-screen sm:max-w-96 -x-96 overflow-x-hidden overflow-y-scroll
              bg-surface-100 shadow transition-all -translate-x-full md:-translate-0"
       data-sidebar-active="false"
       data-sidebar-active-classes="-translate-x-full md:-translate-0" data-sidebar-inactive-classes="-translate-x-full">
    {{-- Content --}}
    {!! $slot !!}
</aside>

<script type="module">
    import * as Sidebar from "{{ Router->staticFilePath("js/Sidebar.js") }}";
    Sidebar.init(768);
</script>
