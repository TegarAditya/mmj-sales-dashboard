<x-filament-panels::page>
    <div class="flex flex-col gap-5">
        @if ($this->semester_id !== '' && $this->semester !== null)
        {{ $this->customerInfolist }}

        <x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :data="
            [
                ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                ...$this->getWidgetData(),
            ]
            "
            :widgets="$this->getWidgets()" />
        @else
        <x-filament::fieldset>
            <div class="text-center">
                <h1 class="text-2xl font-bold">Estimasi Tidak Ditemukan</h1>
                <p class="mt-2 text-gray-600">Kode semester tidak valid. Periksa kembali dengan seksama.</p>
            </div>
        </x-filament::fieldset>
        @endif
    </div>
</x-filament-panels::page>