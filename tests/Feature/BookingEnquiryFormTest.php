<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookingEnquiryFormTest extends TestCase
{
    /**
     * The booking enquiry form is captcha-protected in production. These
     * tests exercise the blueprint validation rules directly, so the
     * captcha is disabled for this form to keep submissions self-contained.
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['captcha.forms' => []]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validSubmission(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Marco Rossi',
            'email' => 'marco@example.com',
            'phone' => '+39 333 123 4567',
            'tour' => 'tuscany1',
            'message_body' => 'Two travellers, twin beds, travelling in May.',
        ], $overrides);
    }

    /**
     * Validate the booking form the same way the live form does — via a
     * Precognition request — so no submission is persisted and no emails
     * or Mailchimp syncing fire as a side effect.
     *
     * @param  array<string, mixed>  $data
     */
    private function precognitiveSubmit(array $data): \Illuminate\Testing\TestResponse
    {
        return $this->postJson('/!/forms/booking_enquiry', $data, ['Precognition' => 'true']);
    }

    public function test_phone_is_required_on_the_booking_enquiry_form(): void
    {
        $this->precognitiveSubmit($this->validSubmission(['phone' => '']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('phone');
    }

    public function test_booking_enquiry_passes_validation_when_phone_is_provided(): void
    {
        $this->precognitiveSubmit($this->validSubmission())
            ->assertSuccessful()
            ->assertJsonMissingValidationErrors('phone');
    }
}
