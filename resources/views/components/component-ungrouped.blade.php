@props(['component' => null])

<div class="overflow-hidden rounded-lg border shadow dark:border-zinc-700">
    <div class="flex flex-col divide-y bg-white dark:bg-zinc-800">
        <ul class="divide-y dark:divide-zinc-700">
            <x-cachet::component :component="$component" />
        </ul>
    </div>
</div>