<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shipping;

class Order extends Model
{
    public const STATUS_NEW        = 'new';
    public const STATUS_ASSIGNED   = 'assigned';
    public const STATUS_DESIGN     = 'design';
    public const STATUS_PRODUCTION = 'production';
    public const STATUS_QC         = 'qc';
    public const STATUS_READY      = 'ready';
    public const STATUS_SHIPPED    = 'shipped';
    public const STATUS_DONE       = 'done';
    public const STATUS_OVERDUE    = 'overdue';

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_ASSIGNED,
        self::STATUS_DESIGN,
        self::STATUS_PRODUCTION,
        self::STATUS_QC,
        self::STATUS_READY,
        self::STATUS_SHIPPED,
        self::STATUS_DONE,
        self::STATUS_OVERDUE,
    ];

    protected $fillable = [
        'order_code',
        'customer_id',
        'user_id',
        'product_type',
        'size',
        'material',
        'quantity',
        'finishing',
        'need_design',
        'deadline',
        'notes',
        'order_status',
        'created_by',
        'subtotal',
        'shipping',
        'discount',
        'total',
        'status',
        'meta',
        'width_cm',
        'height_cm',
        'area_m2',
        'printing_material_id',
        'printing_finishing_id',
        'total_price',
        'priority',
        'due_at',
        'started_at',
        'completed_at',
        'material_cost',
        'finishing_cost',
        'other_cost',
        'total_cost',
        'profit',
        'lifecycle',
        'status',
        'updated_at_client',
        'deadline_reminder_sent_at',
        'sla_status',
        'lead_time_hours',
    ];
    protected $casts = [
        'meta' => 'array',
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'due_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'deadline' => 'date',
        'need_design' => 'boolean',
        'updated_at_client' => 'datetime',
        'deadline_reminder_sent_at' => 'datetime',
        'lead_time_hours' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name'  => 'Unknown Customer',
            'phone' => null,
        ]);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class)->latest();
    }

    public function files()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function operations()
    {
        return $this->hasMany(OrderOperation::class);
    }

    public function qcChecks()
    {
        return $this->hasMany(OrderQcCheck::class);
    }

    public function chats()
    {
        return $this->hasMany(OrderChat::class);
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }
    public function activityLogs()
    {
        return $this->hasMany(OrderActivityLog::class)->latest();
    }
    /**
     * Get the shipping records associated with the order.
     *
     * An order can have multiple shipping entries (e.g. partial shipments).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::STATUSES, true);
    }

    public function canTransitionToByRole(string $role, string $newStatus): bool
    {
        $map = config('order_states.transitions');
        $role = strtolower($role);

        if (!isset($map[$role])) {
            return false;
        }

        $allowedFromCurrent = $map[$role][$this->status] ?? [];
        return in_array($newStatus, $allowedFromCurrent, true);
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function calculateLeadTimeHours(): ?float
    {
        if (!$this->created_at || !$this->updated_at) {
            return null;
        }
        return $this->created_at->diffInMinutes($this->updated_at) / 60;
    }
}
