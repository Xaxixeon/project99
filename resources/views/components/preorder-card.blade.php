<div class="card shadow-sm border-0 rounded-4 p-3 preorder-card"
     data-id="{{ $noteId ?? 'PO-NOTE' }}"
     style="max-width:350px; background:#f8fafc;">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold mb-0">{{ $title ?? 'Pre-Order Note' }}</h6>
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted status" style="font-size:11px;">Idle</small>
            <span class="badge bg-primary">{{ $noteId ?? 'PO-NOTE' }}</span>
        </div>
    </div>
    <input type="hidden" id="note_id_{{ $uid }}" value="{{ $noteId ?? 'PO-NOTE' }}">

    {{-- Form --}}
    <div class="mb-2">
        <label class="form-label small">Customer</label>
        <input type="text" class="form-control form-control-sm po-customer"
               id="customer_name_{{ $uid }}"
               placeholder="Nama Customer">
    </div>

    <div class="mb-2">
        <label class="form-label small">Judul / Catatan</label>
        <input type="text" class="form-control form-control-sm po-title"
               id="title_{{ $uid }}"
               placeholder="Contoh: Cetak Poster A3">
    </div>

    <div class="mb-2">
        <label class="form-label small">Produk</label>
        <select class="form-select form-select-sm po-product" id="product_{{ $uid }}">
            <option value="">Pilih Produk</option>
            <option value="Poster">Poster</option>
            <option value="Banner">Banner</option>
            <option value="Sticker">Sticker</option>
            <option value="Flyer">Flyer</option>
        </select>
    </div>

    <div class="mb-2">
        <label class="form-label small">Catatan Tambahan</label>
        <textarea id="notes_{{ $uid }}" class="form-control form-control-sm po-notes"
                  rows="2" placeholder="Catatan singkat..."></textarea>
    </div>

    <div class="d-flex gap-2">
        <div class="flex-fill">
            <label class="form-label small">Deadline</label>
            <input type="date" class="form-control form-control-sm po-deadline"
                   id="deadline_{{ $uid }}">
        </div>

        <div class="flex-fill">
            <label class="form-label small">Priority</label>
            <select class="form-select form-select-sm po-priority" id="priority_{{ $uid }}">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
            </select>
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success btn-sm flex-fill"
                onclick="savePreorderNote('{{ $uid }}')">Save</button>

        <button class="btn btn-danger btn-sm"
                onclick="deletePreorderNote('{{ $uid }}')">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const uid = "{{ $uid }}";
        const prefix = "preorder_" + uid;
        const card = document.querySelector(`[data-id="{{ $noteId ?? $uid }}"]`);
        if (!card) return;

        let saveTimeout;

        // Restore Offline Data
        const cached = localStorage.getItem(prefix);
        if (cached) {
            const data = JSON.parse(cached);
            restoreCardValues(data);
            setStatus("Offline Save");
        }

        // Listen for typing → autosave local
        card.querySelectorAll("input, textarea, select").forEach(el => {
            el.addEventListener("input", () => {
                updateLocalCache();
            });
        });

        function updateLocalCache() {
            clearTimeout(saveTimeout);

            saveTimeout = setTimeout(() => {
                const data = getCardValues();
                data.sync = false; // pending sync

                localStorage.setItem(prefix, JSON.stringify(data));
                setStatus("Saving...");
            }, 800);
        }

        function getCardValues() {
            return {
                note_id: card.dataset.id,
                customer_name: card.querySelector(".po-customer")?.value || "",
                title: card.querySelector(".po-title")?.value || "",
                product: card.querySelector(".po-product")?.value || "",
                notes: card.querySelector(".po-notes")?.value || "",
                deadline: card.querySelector(".po-deadline")?.value || "",
                priority: card.querySelector(".po-priority")?.value || "Low",
                updated_at: Date.now(),
                sync: false,
            };
        }

        function restoreCardValues(data) {
            card.querySelector(".po-customer").value = data.customer_name ?? data.customer ?? "";
            card.querySelector(".po-title").value = data.title ?? "";
            card.querySelector(".po-product").value = data.product ?? "";
            card.querySelector(".po-notes").value = data.notes ?? "";
            card.querySelector(".po-deadline").value = data.deadline ?? "";
            card.querySelector(".po-priority").value = data.priority ?? "Low";
        }

        function setStatus(text) {
            const el = card.querySelector(".status");
            if (el) el.textContent = text;
        }

        // Try sync when online
        window.addEventListener("online", trySync);

        async function trySync() {
            const cache = localStorage.getItem(prefix);
            if (!cache) return;

            const data = JSON.parse(cache);

            if (data.sync === false) {
                setStatus("Syncing...");

                const res = await fetch("{{ route('preorder.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });

                if (res.ok) {
                    data.sync = true;
                    localStorage.setItem(prefix, JSON.stringify(data));
                    setStatus("Synced ✓");
                } else {
                    setStatus("Sync Failed");
                }
            }
        }

        // Auto-sync every 5 seconds if online
        setInterval(() => {
            if (navigator.onLine) trySync();
        }, 5000);
    });
</script>

@once
    <script>
        const preorderCard = {
            save: async (uid) => {
                const data = {
                    note_id: document.getElementById('note_id_' + uid)?.value,
                    customer_name: document.getElementById('customer_name_' + uid)?.value,
                    title: document.getElementById('title_' + uid)?.value,
                    product: document.getElementById('product_' + uid)?.value,
                    notes: document.getElementById('notes_' + uid)?.value,
                    deadline: document.getElementById('deadline_' + uid)?.value,
                    priority: document.getElementById('priority_' + uid)?.value,
                };

                const res = await fetch("{{ route('preorder.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });

                const json = await res.json();
                if (json.success) {
                    alert("Pre-order saved!");
                } else {
                    alert("Failed!");
                }
            },
            delete: async (uid) => {
                if (!confirm("Delete this note?")) return;
                const noteId = document.getElementById('note_id_' + uid)?.value;
                const res = await fetch("{{ route('preorder.delete') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ note_id: noteId, uid })
                });
                const json = await res.json();
                if (json.success) {
                    alert("Deleted!");
                }
            }
        };

        window.savePreorderNote = preorderCard.save;
        window.deletePreorderNote = preorderCard.delete;

        const refreshPreorderCard = (note) => {
            const card = document.querySelector(`.preorder-card[data-id="${note.note_id}"]`);
            if (!card) return;
            const titleEl = card.querySelector('.po-title');
            const customerEl = card.querySelector('.po-customer');
            const productEl = card.querySelector('.po-product');
            const priorityEl = card.querySelector('.po-priority');
            const notesEl = card.querySelector('.po-notes');
            const deadlineEl = card.querySelector('.po-deadline');

            if (titleEl) titleEl.value = note.title ?? '';
            if (customerEl) customerEl.value = note.customer_name ?? '';
            if (productEl) productEl.value = note.product ?? '';
            if (priorityEl) priorityEl.value = note.priority ?? 'Low';
            if (notesEl) notesEl.value = note.notes ?? '';
            if (deadlineEl) deadlineEl.value = note.deadline ?? '';
        };

        document.addEventListener("DOMContentLoaded", function () {
            if (window.Echo) {
                window.Echo.channel("preorder-channel")
                    .listen(".preorder.updated", (e) => {
                        refreshPreorderCard(e.note);
                    });
            }
        });
    </script>
@endonce
