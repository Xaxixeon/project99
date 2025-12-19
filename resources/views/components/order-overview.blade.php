@props(['order', 'logs', 'ordersByStatus', 'statuses'])

<div class="space-y-6">
    <x-order-timeline :logs="$logs" />
    <x-order-kanban :ordersByStatus="$ordersByStatus" :statuses="$statuses" />
</div>
