<template>
    <span
        class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium whitespace-nowrap"
        :style="pillStyle"
    >
        {{ label }}
    </span>
</template>

<script>
/**
 * Booking-status pill for the form submissions listing in the CP.
 * Receives the raw value (open / booked / closed) from the
 * BookingStatus PHP fieldtype's preProcessIndex output and renders
 * a coloured pill matching the customer-facing treatment on
 * /account/enquiries.
 *
 * Colours are inline styles rather than Tailwind utility classes
 * so the component doesn't depend on which colour swatches the
 * CP's compiled CSS happens to ship — the layout utilities
 * (inline-flex, rounded-full, etc.) are standard and ship with
 * Statamic's CP build.
 */

// Open is the only state that actually needs the admin's attention,
// so it gets the eye-catching colour (blue). Booked is the positive
// completion (green). Closed is just "done" — neutral grey, no
// "rejection" connotation.
const PALETTES = {
    open:   { bg: '#dbeafe', text: '#1d4ed8', border: '#bfdbfe' }, // blue-100 / blue-700 / blue-200
    booked: { bg: '#dcfce7', text: '#15803d', border: '#86efac' }, // green-100 / green-700 / green-300
    closed: { bg: '#f3f4f6', text: '#4b5563', border: '#e5e7eb' }, // gray-100 / gray-600 / gray-200
};

const LABELS = {
    open:   'Open',
    booked: 'Booked',
    closed: 'Closed',
};

export default {
    props: {
        value: {
            type: String,
            default: 'open',
        },
    },

    computed: {
        normalizedValue() {
            return this.value || 'open';
        },

        label() {
            return LABELS[this.normalizedValue] || this.normalizedValue;
        },

        pillStyle() {
            const palette = PALETTES[this.normalizedValue] || PALETTES.open;

            return {
                backgroundColor: palette.bg,
                color: palette.text,
                borderColor: palette.border,
            };
        },
    },
};
</script>
