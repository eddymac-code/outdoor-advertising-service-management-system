<div class="flex items-center justify-between py-9 border-b">
    <h1 class="text-xl font-semibold">{{ $title }}</h1>

    <flux:breadcrumbs>
        @foreach ($breadcrumbs as $crumb)
            <flux:breadcrumbs.item
                href="{{ route($crumb['route']) }}"
                wire:navigate
            >
                {{ $crumb['name'] }}
            </flux:breadcrumbs.item>
        @endforeach
    </flux:breadcrumbs>
</div>
