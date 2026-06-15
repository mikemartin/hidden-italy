<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Bus;
use Illuminate\Testing\TestResponse;
use Statamic\Facades\Form;
use Statamic\Forms\SendEmails;
use Tests\TestCase;

/**
 * The feedback modal (components/_feedback_modal.antlers.html) submits the
 * `feedback` form through Statamic's native form endpoint. These tests cover
 * that endpoint with the same field set the modal sends.
 */
class FeedbackFormTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake([SendEmails::class]);
    }

    protected function tearDown(): void
    {
        Form::find('feedback')->submissions()->each->delete();

        parent::tearDown();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function submitFeedback(array $data): TestResponse
    {
        return $this->postJson('/!/forms/feedback', $data);
    }

    public function test_feedback_is_stored_as_a_form_submission(): void
    {
        $response = $this->submitFeedback([
            'feedback_type' => 'idea',
            'message' => 'It would be great to filter tours by month.',
            'page' => '/tours/guided',
            'email' => 'maria@example.com',
        ]);

        $response->assertOk()->assertJson([
            'success' => true,
            'submission_created' => true,
        ]);

        $submissions = Form::find('feedback')->submissions();
        $this->assertCount(1, $submissions);

        $data = $submissions->first()->data();
        $this->assertSame('idea', $data->get('feedback_type'));
        $this->assertSame('It would be great to filter tours by month.', $data->get('message'));
        $this->assertSame('/tours/guided', $data->get('page'));
        $this->assertSame('maria@example.com', $data->get('email'));

        Bus::assertDispatched(SendEmails::class);
    }

    public function test_email_and_page_may_be_omitted(): void
    {
        $response = $this->submitFeedback([
            'feedback_type' => 'issue',
            'message' => 'The booking form does not load on my phone.',
        ]);

        $response->assertOk()->assertJson(['submission_created' => true]);

        $this->assertCount(1, Form::find('feedback')->submissions());
    }

    public function test_message_is_required(): void
    {
        $response = $this->submitFeedback([
            'feedback_type' => 'issue',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('message');

        $this->assertCount(0, Form::find('feedback')->submissions());
    }

    public function test_feedback_type_must_be_a_known_type(): void
    {
        $response = $this->submitFeedback([
            'feedback_type' => 'spam',
            'message' => 'Buy cheap watches.',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('feedback_type');

        $this->assertCount(0, Form::find('feedback')->submissions());
    }

    public function test_filled_honeypot_fails_silently_without_storing(): void
    {
        $response = $this->submitFeedback([
            'feedback_type' => 'other',
            'message' => 'Hello there.',
            'fax' => '555-0100',
        ]);

        $response->assertOk()->assertJson([
            'success' => true,
            'submission_created' => false,
        ]);

        $this->assertCount(0, Form::find('feedback')->submissions());
        Bus::assertNotDispatched(SendEmails::class);
    }
}
