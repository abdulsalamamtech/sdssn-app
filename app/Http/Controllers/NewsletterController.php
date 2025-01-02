<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsletters = Newsletter::where('active', 'yes')
            ->latest()->paginate();

        if (!$newsletters) {
            return $this->sendError([], 'unable to load newsletters', 500);
        }

        return $this->sendSuccess($newsletters, 'successful', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:newsletters',
        ],[
            'email.required' => 'The email field is required',
            'email.email' => 'The email must be a valid email address',
            'email.unique' => 'The email has already subscribed to our newsletter',
        ]);

        $newsletter = Newsletter::create($data);
        if (!$newsletter) {
            return $this->sendError([], 'unable to subscribe newsletter', 500);
        }

        return $this->sendSuccess($newsletter, 'newsletter subscribed successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsletter $newsletter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newsletter $newsletter)
    {
        //
    }
}
