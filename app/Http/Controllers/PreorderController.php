<?php

namespace App\Http\Controllers;

use App\Events\PreorderUpdated;
use App\Models\PreorderNote;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PreorderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'note_id' => ['nullable', 'string', 'max:255'],
            'updated_at' => ['nullable', 'numeric'],
            'customer_name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'product' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'priority' => ['nullable', 'in:low,medium,high'],
        ]);

        $clientUpdated = $data['updated_at'] ?? now()->timestamp;
        $noteId = $data['note_id'] ?? null;

        $note = $noteId ? PreorderNote::where('note_id', $noteId)->first() : null;

        if (!$note) {
            return $this->createNew($data, $clientUpdated);
        }

        // Jika client punya versi lebih baru → override
        $serverStamp = $note->updated_at_client ? $serverStamp = $note->updated_at_client
            ? Carbon::parse($note->updated_at_client)->timestamp
            : 0 : 0;
        if ($clientUpdated > $serverStamp) {
            return $this->applyClientUpdate($note, $data, $clientUpdated);
        }

        // Jika server lebih baru → cek conflict field-per-field
        return $this->resolveConflict($note, $data, $clientUpdated);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'note_id' => ['nullable', 'string'],
            'id' => ['nullable', 'integer'],
        ]);

        $note = null;

        if ($request->filled('note_id')) {
            $note = PreorderNote::where('note_id', $request->note_id)->first();
        } elseif ($request->filled('id')) {
            $note = PreorderNote::where('id', $request->id)->first();
        }

        if ($note) {
            $note->delete();
            event(new PreorderUpdated($note));
        }

        return response()->json(['success' => true]);
    }

    private function createNew(array $data, int $clientUpdated): JsonResponse
    {
        $noteId = $data['note_id'] ?? $this->generateNoteId();
        $payload = array_merge($data, [
            'note_id' => $noteId,
            'user_id' => auth('staff')->id(), // FIX
            'updated_at_client' => Carbon::createFromTimestamp($clientUpdated),
        ]);

        $note = PreorderNote::create($payload);
        event(new PreorderUpdated($note));

        return response()->json([
            'success' => true,
            'data' => $note,
        ]);
    }

    private function applyClientUpdate(PreorderNote $note, array $data, int $clientUpdated): JsonResponse
    {
        $note->fill($data);
        $note->updated_at_client = Carbon::createFromTimestamp($clientUpdated);
        $note->save();

        event(new PreorderUpdated($note));

        return response()->json([
            'success' => true,
            'data' => $note,
        ]);
    }

    private function resolveConflict(PreorderNote $note, array $data, int $clientUpdated): JsonResponse
    {
        $fields = ['customer_name', 'title', 'product', 'notes', 'deadline', 'priority'];

        $result = [
            'conflicts' => [],
            'merged' => [],
        ];

        foreach ($fields as $field) {
            $clientVal = $data[$field] ?? null;
            $serverVal = $note->$field;

            if ($clientVal === $serverVal) {
                continue;
            }

            // Auto-merge for long text notes (append unique lines)
            if ($field === 'notes' && $clientVal !== null) {
                $merged = $this->mergeNotes((string) $clientVal, (string) $serverVal);
                if ($merged !== $serverVal) {
                    $note->$field = $merged;
                    $result['merged'][$field] = $merged;
                }
                continue;
            }

            // Conflict detected
            $result['conflicts'][$field] = [
                'client' => $clientVal,
                'server' => $serverVal,
            ];
        }

        if (!empty($result['merged'])) {
            $note->updated_at_client = Carbon::createFromTimestamp($clientUpdated);
            $note->save();
            broadcast(new PreorderUpdated($note))->toOthers();
        }

        return response()->json($result);
    }

    private function mergeNotes(string $client, string $server): string
    {
        $clientLines = array_filter(array_map('trim', explode("\n", $client)), fn($l) => $l !== '');
        $serverLines = array_filter(array_map('trim', explode("\n", $server)), fn($l) => $l !== '');

        $merged = array_unique(array_merge($serverLines, $clientLines));
        return implode("\n", $merged);
    }

    private function generateNoteId(): string
    {
        $prefix = 'PO-' . now()->format('Ymd') . '-';
        do {
            $candidate = $prefix . Str::padLeft((string) random_int(0, 9999), 4, '0');
        } while (PreorderNote::where('note_id', $candidate)->exists());

        return $candidate;
    }
}
