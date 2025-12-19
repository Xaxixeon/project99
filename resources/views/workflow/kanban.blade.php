<x-admin.layout>

<h2 class="text-3xl font-bold mb-6">Workflow Kanban</h2>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 overflow-auto">

    @foreach ($columns as $key => $title)

        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-xl font-semibold mb-3">{{ $title }}</h3>

            <div class="kanban-column min-h-[300px] p-2 rounded bg-gray-900"
                 data-status="{{ $key }}">

                @foreach ($orders[$key] ?? [] as $order)
                    <div class="kanban-card bg-gray-700 p-3 rounded mb-2 cursor-move"
                         data-id="{{ $order->id }}">

                        <div class="font-bold">#{{ $order->id }}</div>
                        <div class="text-sm text-gray-300">
                            {{ $order->customer->name ?? '-' }}
                        </div>

                        <div class="mt-2 space-x-1">
                            <button class="bg-indigo-600 text-xs px-2 py-1 rounded"
                                    onclick="openActionMenu({{ $order->id }})">
                                Actions
                            </button>
                            <button class="bg-gray-600 text-xs px-2 py-1 rounded"
                                    onclick="openOrderModal({{ $order->id }})">
                                Detail
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    @endforeach

</div>

{{-- ACTION MENU --}}
<div id="actionMenu"
     class="fixed hidden bg-gray-800 text-white p-4 rounded shadow-xl z-50">

    <div class="font-bold mb-2">Actions</div>

    <div id="actionButtons"></div>

    <button onclick="closeActionMenu()"
            class="mt-3 w-full bg-red-600 px-3 py-1 rounded">
        Close
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
const allowedActions = @json($allowed ?? []);
const actionLabels = {
    start_design:     "Start Design",
    submit_design:    "Submit Design",
    reject_design:    "Request Revision",
    approve_design:   "Approve Design",
    start_print:      "Start Printing",
    finish_print:     "Finish Printing",
    start_finish:     "Start Finishing",
    finish_finish:    "Finish Finishing",
    qc_pass:          "QC Pass",
    qc_fail:          "QC Fail",
    pack:             "Pack Order",
    complete:         "Mark Completed"
};

document.querySelectorAll('.kanban-column').forEach(col => {
    new Sortable(col, {
        group: 'kanban',
        animation: 150,
        onEnd: evt => {
            let orderId = evt.item.getAttribute('data-id');
            let newStatus = evt.to.getAttribute('data-status');

            fetch("{{ route('workflow.update-status') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: newStatus
                })
            });
        }
    });
});


function openActionMenu(orderId) {
    let menu = document.getElementById('actionMenu');
    let btns = document.getElementById('actionButtons');

    btns.innerHTML = "";

    if (allowedActions.length === 0) {
        btns.innerHTML = `<div class="text-xs text-gray-400">No actions available</div>`;
    } else {
        allowedActions.forEach(action => {
            btns.innerHTML += `
                <button onclick="sendAction(${orderId}, '${action}')"
                        class="w-full bg-gray-700 hover:bg-gray-600 px-3 py-1 rounded mb-1">
                    ${actionLabels[action] ?? action}
                </button>
            `;
        });
    }

    menu.style.display = 'block';
    menu.style.left = (event.pageX + 10) + 'px';
    menu.style.top  = (event.pageY + 10) + 'px';
}

function closeActionMenu() {
    document.getElementById('actionMenu').style.display = 'none';
}

function sendAction(orderId, action) {
    fetch("{{ route('workflow.action') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            order_id: orderId,
            action: action
        })
    }).then(() => {
        closeActionMenu();
        location.reload();
    });
}
</script>

</x-admin.layout>
