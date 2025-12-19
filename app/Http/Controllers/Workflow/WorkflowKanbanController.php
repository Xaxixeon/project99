<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class WorkflowKanbanController extends Controller
{
    public $columns = [
        'new'               => 'New Orders',
        'designing'         => 'Designing',
        'waiting_approval'  => 'Approval',
        'printing'          => 'Printing',
        'finishing'         => 'Finishing',
        'qc'                => 'QC',
        'packaging'         => 'Packaging',
        'ready'             => 'Ready',
        'completed'         => 'Completed',
    ];

    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = auth('staff')->user();
        $userRole = $user?->firstRoleName() ?? ($user->role ?? 'unknown');

        $orders = Order::with('customer')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('status');

        $allowed = $this->allowedActions($userRole);
        $visible = $this->visibleColumns($userRole);

        return view('workflow.kanban', [
            'columns' => array_filter($this->columns, fn($k) => in_array($k, $visible), ARRAY_FILTER_USE_KEY),
            'orders'  => $orders,
            'userRole' => $userRole,
            'allowed' => $allowed,
            'visible' => $visible,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'status'   => 'required|string',
        ]);

        Order::where('id', $request->order_id)
            ->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    /**
     * Tombol aksi per status
     */
    public function action(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'action'   => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        /** @var \App\Models\User|null $staff */
        $staff = auth('staff')->user();
        $role = $staff?->firstRoleName() ?? ($staff->role ?? 'unknown');
        $allowed = $this->allowedActions($role);

        if (!in_array($request->action, $allowed, true) && $role !== 'superadmin') {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        switch ($request->action) {

            case 'start_design':
                $order->status = 'designing';
                break;

            case 'submit_design':
                $order->status = 'waiting_approval';
                break;

            case 'reject_design':
                $order->status = 'designing';
                break;

            case 'approve_design':
                $order->status = 'printing';
                break;

            case 'start_print':
                $order->status = 'printing';
                break;

            case 'finish_print':
                $order->status = 'finishing';
                break;

            case 'start_finish':
                $order->status = 'finishing';
                break;

            case 'finish_finish':
                $order->status = 'qc';
                break;

            case 'qc_pass':
                $order->status = 'packaging';
                break;

            case 'qc_fail':
                $order->status = 'printing';
                break;

            case 'pack':
                $order->status = 'ready';
                break;

            case 'complete':
                $order->status = 'completed';
                break;

            default:
                return response()->json(['error' => 'Unknown action'], 400);
        }

        $order->save();

        return response()->json(['success' => true]);
    }

    public function allowedActions(string $role): array
    {
        return match ($role) {
            'admin' => [
                'start_design',
                'submit_design',
                'reject_design',
                'approve_design',
                'start_print',
                'finish_print',
                'start_finish',
                'finish_finish',
                'qc_pass',
                'qc_fail',
                'pack',
                'complete',
            ],
            'customer_service' => [
                'approve_design',
                'reject_design',
                'complete',
            ],
            'designer' => [
                'start_design',
                'submit_design',
                'reject_design',
            ],
            'production' => [
                'start_print',
                'finish_print',
            ],
            'finishing' => [
                'start_finish',
                'finish_finish',
            ],
            'qc' => [
                'qc_pass',
                'qc_fail',
            ],
            'warehouse' => [
                'pack',
                'complete',
            ],
            'cashier' => [
                // mark paid not modeled in this kanban; no state change here
            ],
            'manager' => [
                // monitoring only
            ],
            'superadmin' => [
                'start_design',
                'submit_design',
                'reject_design',
                'approve_design',
                'start_print',
                'finish_print',
                'start_finish',
                'finish_finish',
                'qc_pass',
                'qc_fail',
                'pack',
                'complete',
            ],
            default => [],
        };
    }

    public function visibleColumns(string $role): array
    {
        return match ($role) {
            'admin' => array_keys($this->columns),
            'designer' => ['new', 'designing', 'waiting_approval'],
            'customer_service' => ['new', 'designing', 'waiting_approval'],
            'production' => ['printing', 'finishing'],
            'finishing' => ['finishing', 'qc'],
            'qc' => ['qc', 'packaging'],
            'warehouse' => ['packaging', 'ready', 'completed'],
            'cashier' => ['ready', 'completed'],
            'manager' => array_keys($this->columns),
            'superadmin' => array_keys($this->columns),
            default => [],
        };
    }
}
