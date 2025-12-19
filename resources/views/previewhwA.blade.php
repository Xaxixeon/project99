<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview HWA - Preorder Test</title>
    <style>
        :root {
            --blue: #1a7bff;
            --border: #e5e7eb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { background: #f5f6f8; color: #111827; padding: 20px; }
        .page { max-width: 1100px; margin: 0 auto; }
        .monday-header {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .monday-title { font-size: 20px; font-weight: 700; }
        /* Preorder section */
        #preorder-wrapper { width: 100%; }
        #preorder-header {
            border: 1px solid var(--border);
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.06);
        }
        #preorder-arrow.collapsed { transform: rotate(-90deg); }
        .hidden { display: none; }
        .preorder-card-row { width: 100%; }
        .preorder-card {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #dce8ff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(16,112,224,0.12);
            padding: 14px;
        }
        .preorder-card h4 { margin-bottom: 6px; font-size: 15px; font-weight: 700; }
        .preorder-card .meta { font-size: 13px; color: #475569; margin-bottom: 8px; }
        /* board placeholder */
        #boards {
            margin-top: 20px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.06);
        }
    </style>
</head>
<body>
<div class="page">
    <!-- MONDAY HEADER -->
    <div class="monday-header">
        <div class="monday-title">Monday Header Mock</div>
        <div class="text-sm text-gray-500">Testing Pre-Order Section</div>
    </div>

    <!-- ===================== PRE-ORDER SECTION ===================== -->
    <div id="preorder-wrapper" class="w-full mt-4" style="margin-top:16px;">

        <!-- HEADER BAR (COLLAPSIBLE) -->
        <div id="preorder-header"
             class="flex items-center justify-between px-4 py-3 rounded-lg border bg-white shadow hover:bg-gray-50 cursor-pointer transition-all"
             style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;gap:10px;">

            <div class="flex items-center gap-2" style="display:flex;align-items:center;gap:8px;">
                <span id="preorder-arrow" class="text-xl transition-transform" style="font-size:18px;">▾</span>
                <h2 class="text-xl font-semibold" style="font-size:18px;font-weight:600;">📝 Pre-Order Notes</h2>
                <span id="preorder-count"
                      class="ml-2 text-sm px-2 py-0.5 rounded-full bg-blue-100 text-blue-700"
                      style="margin-left:6px;font-size:12px;padding:4px 8px;border-radius:12px;background:#e1efff;color:#1d4ed8;">
                      0
                </span>
            </div>

            <!-- Monday-style Blue Button -->
            <button id="add-preorder-btn"
                    class="rounded-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 font-medium transition-all shadow"
                    style="border:none;border-radius:999px;background:var(--blue);color:#fff;padding:8px 14px;font-weight:600;cursor:pointer;">
                + New pre-order
            </button>
        </div>

        <!-- COLLAPSIBLE CONTENT -->
        <div id="preorder-content" class="mt-3" style="margin-top:12px;">
            <div id="preorder-cards" class="flex flex-col gap-4" style="display:flex;flex-direction:column;gap:12px;">
            </div>
        </div>

    </div>
    <!-- ================= END PRE-ORDER SECTION ================== -->

    <!-- BOARD CONTENT PLACEHOLDER -->
    <div id="boards" style="margin-top:20px;">
        <h3 style="font-size:16px;font-weight:700;margin-bottom:8px;">Board Content Placeholder</h3>
        <p style="color:#475569;">Letakkan konten board Anda di sini. Pre-order section berada tepat di atas area ini.</p>
    </div>
</div>

<script>
    // Mock card data
    let cards = [
        { id: 'PO-001', title: 'Cetak Banner A3', customer: 'Budi', product: 'Banner', priority: 'High' },
        { id: 'PO-002', title: 'Sticker Promo', customer: 'Sari', product: 'Sticker', priority: 'Medium' },
    ];

    // Collapse / Expand
    const preorderHeader = document.getElementById("preorder-header");
    const preorderContent = document.getElementById("preorder-content");
    const preorderArrow   = document.getElementById("preorder-arrow");
    const preorderCount   = document.getElementById("preorder-count");
    const cardsContainer  = document.getElementById("preorder-cards");
    const addBtn          = document.getElementById("add-preorder-btn");

    preorderHeader.addEventListener("click", (e) => {
        if (e.target === addBtn) return;
        preorderContent.classList.toggle("hidden");
        preorderArrow.classList.toggle("collapsed");
    });

    function renderCards() {
        cardsContainer.innerHTML = "";
        cards.forEach((card) => {
            const div = document.createElement("div");
            div.className = "preorder-card preorder-card-row";
            div.innerHTML = `
                <h4>${card.title}</h4>
                <div class="meta">Customer: ${card.customer} • Product: ${card.product} • Priority: ${card.priority}</div>
                <div class="meta">ID: ${card.id}</div>
            `;
            cardsContainer.appendChild(div);
        });
        updatePreorderCount();
    }

    function updatePreorderCount() {
        preorderCount.textContent = cards.length;
    }

    function addNewCard() {
        const next = cards.length + 1;
        cards.push({
            id: `PO-${String(next).padStart(3, '0')}`,
            title: `New Pre-Order ${next}`,
            customer: "Customer " + next,
            product: "Product",
            priority: "Low"
        });
        renderCards();
    }

    addBtn.addEventListener("click", () => addNewCard());

    renderCards();
</script>
</body>
</html>
