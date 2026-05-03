/**
 * Control Panel customisations.
 * https://statamic.dev/extending/control-panel
 */

import BookingStatusIndexFieldtype from './components/fieldtypes/BookingStatusIndexFieldtype.vue';

Statamic.booting(() => {
    // Coloured pill for the booking-status column in form submission
    // listings. The PHP fieldtype lives in
    // app/Fieldtypes/BookingStatus.php and inherits the standard
    // Select fieldtype's edit UI, so we only register the index
    // (listing) component here.
    Statamic.$components.register('booking_status-fieldtype-index', BookingStatusIndexFieldtype);
});
