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

<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Seminaranmeldung</h1>
        <p class="mt-1 text-sm text-gray-500">Melden Sie sich und Ihr Praxisteam für ein Fachseminar an.</p>
    </div>

    <form id="booking-form" wire:submit="save" class="flex flex-col gap-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-1.5">
            <label for="workshop_id" class="text-sm font-medium">Seminar</label>
            <select id="workshop_id" wire:model.live="form.workshop_id"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                <option value="">Bitte Seminar wählen…</option>
                @foreach ($this->workshops as $workshop)
                    <option value="{{ $workshop->id }}" wire:key="workshop-{{ $workshop->id }}">
                        {{ $workshop->name }} ({{ $this->formatPrice($workshop->price) }})
                    </option>
                @endforeach
            </select>
            @error('form.workshop_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-[10rem_1fr]">
            <div class="flex flex-col gap-1.5">
                <label for="salutation" class="text-sm font-medium">Anrede</label>
                <select id="salutation" wire:model.live="form.salutation"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    <option value="">Bitte wählen…</option>
                    @foreach (App\Enums\Salutation::cases() as $case)
                        <option value="{{ $case->value }}" wire:key="salutation-{{ $case->value }}">
                            {{ $case->label() }}
                        </option>
                    @endforeach
                </select>
                @error('form.salutation') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="first_name" class="text-sm font-medium">Vorname</label>
                <input id="first_name" type="text" wire:model.live="form.first_name"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                @error('form.first_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="last_name" class="text-sm font-medium">Nachname</label>
            <input id="last_name" type="text" wire:model.live="form.last_name"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
            @error('form.last_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-medium">E-Mail-Adresse</label>
            <input id="email" type="email" wire:model.live="form.email"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
            @error('form.email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="person_count" class="text-sm font-medium">Teilnehmerzahl</label>
            <input id="person_count" type="number" min="1" wire:model.live="form.person_count"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 sm:w-40">
            @error('form.person_count') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="notes" class="text-sm font-medium">Anmerkungen <span class="font-normal text-gray-500">(optional)</span></label>
            <textarea id="notes" rows="4" wire:model.live="form.notes"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20"></textarea>
            @error('form.notes') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </form>

    @php
        $previewName = trim(implode(' ', array_filter([
            Salutation::tryFrom($form->salutation)?->label(),
            $form->first_name,
            $form->last_name,
        ], fn (?string $part): bool => $part !== null && trim($part) !== '')));
    @endphp

    <div class="rounded-xl border border-blue-200 bg-blue-50/60 p-6">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-blue-800">Vorschau Ihrer Anmeldung</h2>
        <dl class="mt-4 divide-y divide-blue-200/60 text-sm">
            <div class="flex items-baseline justify-between gap-6 py-3">
                <dt class="shrink-0 text-gray-500">Teilnehmer</dt>
                <dd class="text-right font-medium">
                    @if ($previewName !== '')
                        {{ $previewName }}
                    @else
                        <span class="font-normal text-gray-400">–</span>
                    @endif
                </dd>
            </div>

            <div class="flex items-baseline justify-between gap-6 py-3">
                <dt class="shrink-0 text-gray-500">E-Mail</dt>
                <dd class="break-all text-right font-medium">
                    @if (trim($form->email) !== '')
                        {{ $form->email }}
                    @else
                        <span class="font-normal text-gray-400">–</span>
                    @endif
                </dd>
            </div>

            <div class="flex items-baseline justify-between gap-6 py-3">
                <dt class="shrink-0 text-gray-500">Seminar</dt>
                <dd class="text-right">
                    @if ($this->selectedWorkshop)
                        <span class="font-medium">{{ $this->selectedWorkshop->name }}</span>
                        <span class="mt-0.5 block text-xs text-gray-500">
                            {{ $form->person_count }} &times; {{ $this->formatPrice($this->selectedWorkshop->price) }}
                        </span>
                    @else
                        <span class="text-gray-400">–</span>
                    @endif
                </dd>
            </div>

            @if ($form->notes !== '')
                <div class="flex items-baseline justify-between gap-6 py-3">
                    <dt class="shrink-0 text-gray-500">Anmerkung</dt>
                    <dd class="text-right italic text-gray-600">„{{ $form->notes }}“</dd>
                </div>
            @endif
        </dl>
        <p class="mt-1 flex items-baseline justify-between gap-6 border-t border-blue-300/60 pt-4">
            <span class="text-sm font-medium">Gesamtpreis</span>
            @if ($this->totalPrice !== null)
                <span class="text-lg font-semibold tracking-tight">{{ $this->formatPrice($this->totalPrice) }}</span>
            @else
                <span class="text-gray-400">–</span>
            @endif
        </p>
    </div>

    @if (session('status'))
        <p class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            {{ session('status') }}
        </p>
    @endif

    <button type="submit" form="booking-form" wire:loading.attr="disabled" wire:target="save"
        class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600/40 disabled:opacity-50">
        Jetzt verbindlich anmelden
    </button>
</div>
