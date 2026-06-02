<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteEnquiryFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_website_enquiry_form_stores_data(): void
    {
        $response = $this->post(route('website.enquiry.store'), [
            'name' => 'John Doe',
            'contact_number' => '9876543210',
            'email' => 'john@example.com',
            'message' => 'I want details about the admission process.',
        ], [
            'HTTP_REFERER' => 'https://school.example/contact',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('enquiry_success');

        $this->assertDatabaseHas('website_enquiries', [
            'name' => 'John Doe',
            'contact_number' => '9876543210',
            'email' => 'john@example.com',
            'message' => 'I want details about the admission process.',
            'notes' => 'school.example',
        ]);
    }
}

