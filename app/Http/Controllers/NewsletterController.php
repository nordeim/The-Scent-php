<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    /**
     * Handle the newsletter subscription request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $subscriber = Subscriber::create($validator->validated());

            // Optional: Send a confirmation email here

            return response()->json([
                'message' => 'Successfully subscribed!',
                'subscriber' => $subscriber,
            ], 201);

        } catch (\Exception $e) {
            // Log the error
            logger()->error('Newsletter subscription failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
    
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:newsletter_subscriptions,email'
        ]);
        
        $subscription = NewsletterSubscription::where('email', $validated['email'])->first();
        $subscription->update(['status' => 'unsubscribed']);
        
        return response()->json([
            'message' => 'You have been unsubscribed from our newsletter.'
        ]);
    }
    
    public function resubscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:newsletter_subscriptions,email'
        ]);
        
        $subscription = NewsletterSubscription::where('email', $validated['email'])->first();
        $subscription->update(['status' => 'active']);
        
        return response()->json([
            'message' => 'You have been resubscribed to our newsletter.'
        ]);
    }
    
    public function preferences(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:newsletter_subscriptions,email',
            'preferences' => 'required|array',
            'preferences.essential_oils' => 'boolean',
            'preferences.soaps' => 'boolean',
            'preferences.lifestyle' => 'boolean',
            'preferences.promotions' => 'boolean',
            'preferences.news' => 'boolean'
        ]);
        
        $subscription = NewsletterSubscription::where('email', $validated['email'])->first();
        $subscription->update([
            'preferences' => $validated['preferences']
        ]);
        
        return response()->json([
            'message' => 'Your newsletter preferences have been updated.'
        ]);
    }
    
    public function sendNewsletter(Request $request)
    {
        $this->authorize('send-newsletter');
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:all,essential_oils,soaps,lifestyle',
            'test_mode' => 'boolean'
        ]);
        
        $query = NewsletterSubscription::where('status', 'active');
        
        // Filter by preferences if not sending to all
        if ($validated['type'] !== 'all') {
            $query->whereJsonContains('preferences->' . $validated['type'], true);
        }
        
        $subscriptions = $query->get();
        
        if ($validated['test_mode'] ?? false) {
            // Send test email to admin
            Mail::to(auth()->user()->email)
                ->send(new \App\Mail\Newsletter($validated['subject'], $validated['content']));
                
            return response()->json([
                'message' => 'Test newsletter sent successfully.',
                'recipients_count' => 1
            ]);
        }
        
        // Send to all subscribers
        foreach ($subscriptions as $subscription) {
            Mail::to($subscription->email)
                ->send(new \App\Mail\Newsletter($validated['subject'], $validated['content']));
                
            // Add delay to prevent overwhelming the mail server
            sleep(1);
        }
        
        return response()->json([
            'message' => 'Newsletter sent successfully.',
            'recipients_count' => $subscriptions->count()
        ]);
    }
    
    public function previewNewsletter(Request $request)
    {
        $this->authorize('send-newsletter');
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        
        return view('emails.newsletter', [
            'subject' => $validated['subject'],
            'content' => $validated['content']
        ]);
    }
} 