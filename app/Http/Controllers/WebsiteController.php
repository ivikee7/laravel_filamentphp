<?php

namespace App\Http\Controllers;

use App\Models\WebsiteEnquiry;
use App\Models\WebsitePage;
use App\Models\WebsiteMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    /**
     * Show the home page.
     */
    public function home(): View
    {
        $homePage = WebsitePage::where('is_home', true)
            ->where('status', 'published')
            ->first();

        return view('website.home', ['page' => $homePage]);
    }

    /**
     * Show a specific page by slug.
     */
    public function show(string $slug): View
    {
        $page = WebsitePage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('website.page', ['page' => $page]);
    }

    /**
     * Get menu items for the navigation.
     */
    public function getMenuItems(string $location = 'header'): array
    {
        return WebsiteMenu::where('location', $location)
            ->where('is_active', true)
            ->with(['items' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with('page');
            }])
            ->first()
            ?->items
            ->toArray() ?? [];
    }

    /**
     * Store enquiry from website form.
     */
    public function submitEnquiry(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'contact_number' => ['required', 'digits:10'],
            'email' => ['required', 'email', 'max:50'],
            'message' => ['required', 'string', 'max:255'],
        ]);

        $exists = WebsiteEnquiry::where('name', $validated['name'])
            ->where('contact_number', $validated['contact_number'])
            ->where('email', $validated['email'])
            ->where('message', $validated['message'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['duplicate' => 'This exact enquiry has already been submitted.'])
                ->withInput()
                ->withFragment('enquiry-form');
        }

        $source = $request->headers->get('origin')
            ?? $request->headers->get('referer')
            ?? 'Direct/Unknown';

        $domain = parse_url($source, PHP_URL_HOST) ?? $source;
        $validated['notes'] = substr((string) $domain, 0, 150);

        WebsiteEnquiry::create($validated);

        return back()
            ->with('enquiry_success', 'Thank you. Your enquiry has been submitted successfully.')
            ->withFragment('enquiry-form');
    }

}

