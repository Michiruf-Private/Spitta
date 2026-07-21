<?php

it('renders the booking create page', function () {
    $this->get(route('bookings.create'))
        ->assertOk()
        ->assertSeeLivewire('booking.create');
});
