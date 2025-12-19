<x-admin.layout>

    <h2 class="text-3xl font-bold mb-6">Queue Monitor</h2>

    <div x-data="queueMonitor()" x-init="load()" class="space-y-8">

        {{-- Worker Status --}}
        <div class="bg-gray-800 p-6 rounded-xl shadow">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Worker Status</h3>

                <span class="px-4 py-2 rounded-full text-sm"
                    :class="worker.status === 'running' ?
                        'bg-green-600 text-white' :
                        'bg-red-600 text-white'"
                    x-text="worker.status === 'running' ? 'Running' : 'Not Running'">
                </span>
            </div>

            <p class="text-gray-400 text-sm mt-2">Last update: <span x-text="worker.timestamp"></span></p>
            <p class="text-gray-400 text-sm">Memory Usage: <span x-text="worker.memory"></span></p>
        </div>

        {{-- Pending Jobs --}}
        <div class="bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-xl font-semibold mb-3">Pending Jobs</h3>

            <template x-if="jobs.length === 0">
                <p class="text-gray-500">No pending jobs.</p>
            </template>

            <template x-for="job in jobs" :key="job.id">
                <div class="p-4 border-b border-gray-700">
                    <p class="font-semibold">Job ID: <span x-text="job.id"></span></p>
                    <p class="text-sm text-gray-400">Queue: <span x-text="job.queue"></span></p>
                    <p class="text-sm text-gray-400">Created: <span x-text="job.created_at"></span></p>
                </div>
            </template>
        </div>

        {{-- Failed Jobs --}}
        <div class="bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-xl font-semibold mb-3">Failed Jobs</h3>

            <template x-if="failed.length === 0">
                <p class="text-gray-500">No failed jobs.</p>
            </template>

            <template x-for="job in failed" :key="job.id">
                <div class="p-4 border-b border-red-700">
                    <p class="font-semibold text-red-400">Failed ID: <span x-text="job.id"></span></p>
                    <p class="text-sm text-gray-400">Exception: <span
                            x-text="(job.exception || '').substring(0, 60) + '...'"></span></p>
                    <p class="text-sm text-gray-400">Failed at: <span x-text="job.failed_at"></span></p>
                </div>
            </template>
        </div>
    </div>

    <script>
        function queueMonitor() {
            return {
                jobs: [],
                failed: [],
                worker: {},

                load() {
                    this.fetchData();
                    setInterval(() => this.fetchData(), 5000);
                },

                fetchData() {
                    fetch('{{ route('admin.queue.monitor.poll') }}')
                        .then(res => res.json())
                        .then(data => {
                            this.jobs = data.jobs || [];
                            this.failed = data.failed || [];
                            this.worker = data.worker || {};
                        });
                }
            }
        }
    </script>

</x-admin.layout>
