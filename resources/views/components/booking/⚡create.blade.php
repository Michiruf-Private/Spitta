<?php

use App\Enums\Salutation;
use App\Livewire\Forms\BookingForm;
use App\Models\Workshop;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public BookingForm $form;

    /**
     * @return Collection<int, Workshop>
     */
    #[Computed]
    public function workshops(): Collection
    {
        return Workshop::query()->orderBy('name')->get();
    }

    #[Computed]
    public function selectedWorkshop(): ?Workshop
    {
        return $this->workshops->firstWhere('id', $this->form->workshop_id);
    }

    #[Computed]
    public function totalPrice(): ?string
    {
        if ($this->selectedWorkshop === null) {
            return null;
        }

        return bcmul($this->selectedWorkshop->price, (string) $this->form->person_count, 2);
    }

    public function formatPrice(string $price): string
    {
        [$euros, $cents] = explode('.', bcadd($price, '0', 2));

        return number_format((int) $euros, 0, ',', '.').','.$cents.' €';
    }

    public function save(): void
    {
        $booking = $this->form->store();

        session()->flash('status', sprintf(
            'Vielen Dank, %s %s — Ihre Anmeldung für %s wurde erhalten.',
            $booking->salutation->label(),
            $booking->last_name,
            $booking->workshop->name,
        ));
    }
};
?>

<div>
    <h1>Book a workshop</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <form wire:submit="save">
        <label for="workshop_id">Workshop</label>
        <select id="workshop_id" wire:model.live="form.workshop_id">
            <option value="">Select a workshop…</option>
            @foreach ($this->workshops as $workshop)
                <option value="{{ $workshop->id }}" wire:key="workshop-{{ $workshop->id }}">
                    {{ $workshop->name }} ({{ $workshop->price }})
                </option>
            @endforeach
        </select>
        @error('form.workshop_id') {{ $message }} @enderror
        <br>

        <label for="salutation">Salutation</label>
        <select id="salutation" wire:model.live="form.salutation">
            <option value="">Select a salutation…</option>
            @foreach (App\Enums\Salutation::cases() as $case)
                <option value="{{ $case->value }}" wire:key="salutation-{{ $case->value }}">
                    {{ $case->label() }}
                </option>
            @endforeach
        </select>
        @error('form.salutation') {{ $message }} @enderror
        <br>

        <label for="first_name">First name</label>
        <input id="first_name" type="text" wire:model.live="form.first_name">
        @error('form.first_name') {{ $message }} @enderror
        <br>

        <label for="last_name">Last name</label>
        <input id="last_name" type="text" wire:model.live="form.last_name">
        @error('form.last_name') {{ $message }} @enderror
        <br>

        <label for="email">Email</label>
        <input id="email" type="email" wire:model.live="form.email">
        @error('form.email') {{ $message }} @enderror
        <br>

        <label for="person_count">Number of persons</label>
        <input id="person_count" type="number" min="1" wire:model.live="form.person_count">
        @error('form.person_count') {{ $message }} @enderror
        <br>

        <label for="notes">Notes (optional)</label>
        <textarea id="notes" rows="4" wire:model.live="form.notes"></textarea>
        @error('form.notes') {{ $message }} @enderror
        <br>

        @if ($form->isComplete() && $this->selectedWorkshop)
            <h2>Preview</h2>
            <p>
                {{ Salutation::tryFrom($form->salutation)?->label().' '.$form->first_name.' '.$form->last_name }} – {{ $form->email }} bucht:<br>

                Seminar: {{ $this->selectedWorkshop->name }}
                ({{ $form->person_count }}x {{ $this->formatPrice($this->selectedWorkshop->price) }})
                = {{ $this->formatPrice($this->totalPrice) }}<br>
                @if ($form->notes !== '')
                    Anmerkung: „{{ $form->notes }}“
                @endif
            </p>
        @endif

        <button type="submit">Book now</button>
    </form>
</div>
