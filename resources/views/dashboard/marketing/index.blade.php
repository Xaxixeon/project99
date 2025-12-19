<x-admin.layout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <x-stat-card title="Leads Baru" value="{{ $leads ?? 0 }}" bg="blue" />
        <x-stat-card title="Konversi (%)" value="{{ $conversion ?? 0 }}%" bg="green" />
        <x-stat-card title="Campaign Aktif" value="{{ $activeCampaign ?? 0 }}" bg="yellow" />
    </div>

    <x-card class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Leads Terbaru</h2>
        <x-table>
            <x-slot name="head">
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Kontak</th>
                <th class="p-3 text-left">Sumber</th>
                <th class="p-3 text-left">Status</th>
            </x-slot>

            @forelse($recentLeads as $lead)
                <tr class="border-t">
                    <td class="p-3">{{ $lead->name }}</td>
                    <td class="p-3">{{ $lead->contact }}</td>
                    <td class="p-3">{{ $lead->source }}</td>
                    <td class="p-3">
                        <x-badge color="{{ $lead->status === 'converted' ? 'green' : 'yellow' }}">
                            {{ ucfirst($lead->status) }}
                        </x-badge>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-3 text-gray-500">Belum ada leads.</td>
                </tr>
            @endforelse
        </x-table>
    </x-card>

    <x-card>
        <h2 class="text-lg font-semibold mb-4">Catatan Campaign</h2>
        <p class="text-gray-600 text-sm">
            Tambahkan nanti: grafik performa campaign per channel, CTR, biaya per lead, dll.
        </p>
    </x-card>
</x-admin.layout>
