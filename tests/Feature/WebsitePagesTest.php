<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebsitePagesTest extends TestCase
{
    public function test_public_website_pages_render_successfully(): void
    {
        $this->get('/')->assertOk()->assertSee('Modern learning for bright futures.');
        $this->get('/about')->assertOk()->assertSee('A school experience designed for the next generation.');
        $this->get('/academics')->assertOk()->assertSee('A strong curriculum from early years to board preparation.');
        $this->get('/admissions')->assertOk()->assertSee('Join a school that welcomes your child with care and clarity.');
        $this->get('/contact')->assertOk()->assertSee('We are here to help you visit, enquire, or start the admissions journey.');
    }

    public function test_root_routes_to_public_site_not_admin(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Riverstone School');
        $response->assertDontSee('/admin');
    }
}

