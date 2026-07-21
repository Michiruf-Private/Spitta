<?php

namespace App\Livewire\Forms;

use App\Enums\Salutation;
use App\Models\Booking;
use Illuminate\Validation\Rule;
use Livewire\Form;

class BookingForm extends Form
{
    public ?int $workshop_id = null;

    public string $salutation = '';

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public int $person_count = 1;

    public string $notes = '';

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'workshop_id' => ['required', 'integer', Rule::exists('workshops', 'id')],
            'salutation' => ['required', Rule::enum(Salutation::class)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'person_count' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function isComplete(): bool
    {
        return $this->workshop_id !== null
            && Salutation::tryFrom($this->salutation) !== null
            && trim($this->first_name) !== ''
            && trim($this->last_name) !== ''
            && trim($this->email) !== ''
            && $this->person_count >= 1;
    }

    public function store(): Booking
    {
        $validated = $this->validate();

        if (str($validated['notes'])->trim()->isEmpty()) {
            $validated['notes'] = null;
        }

        $booking = Booking::create($validated);

        $this->reset();

        return $booking;
    }
}
