<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'phone' => 'nullable|string|max:20',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);
        
        // Store message in database
        $message = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'phone' => $validated['phone'] ?? null,
            'status' => 'new'
        ]);
        
        // Send notification email to admin
        Mail::to(config('mail.admin.address'))
            ->send(new \App\Mail\ContactNotification($message));
            
        // Send confirmation email to user
        Mail::to($message->email)
            ->send(new \App\Mail\ContactConfirmation($message));
            
        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
    
    public function index()
    {
        $this->authorize('view-contact-messages');
        
        $messages = ContactMessage::latest()
            ->paginate(20);
            
        return view('admin.contact.index', compact('messages'));
    }
    
    public function showMessage(ContactMessage $message)
    {
        $this->authorize('view-contact-messages');
        
        return view('admin.contact.show', compact('message'));
    }
    
    public function updateStatus(Request $request, ContactMessage $message)
    {
        $this->authorize('update-contact-messages');
        
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,archived'
        ]);
        
        $message->update($validated);
        
        return back()->with('success', 'Message status updated successfully.');
    }
    
    public function reply(Request $request, ContactMessage $message)
    {
        $this->authorize('reply-contact-messages');
        
        $validated = $request->validate([
            'reply' => 'required|string|max:5000'
        ]);
        
        // Send reply email
        Mail::to($message->email)
            ->send(new \App\Mail\ContactReply($message, $validated['reply']));
            
        // Update message status
        $message->update([
            'status' => 'replied',
            'replied_at' => now(),
            'reply' => $validated['reply']
        ]);
        
        return back()->with('success', 'Reply sent successfully.');
    }
    
    public function destroy(ContactMessage $message)
    {
        $this->authorize('delete-contact-messages');
        
        $message->delete();
        
        return redirect()->route('admin.contact.index')
            ->with('success', 'Message deleted successfully.');
    }
    
    public function bulkAction(Request $request)
    {
        $this->authorize('manage-contact-messages');
        
        $validated = $request->validate([
            'action' => 'required|in:delete,mark_read,mark_replied,mark_archived',
            'messages' => 'required|array',
            'messages.*' => 'exists:contact_messages,id'
        ]);
        
        $messages = ContactMessage::whereIn('id', $validated['messages'])->get();
        
        switch ($validated['action']) {
            case 'delete':
                foreach ($messages as $message) {
                    $message->delete();
                }
                $message = 'Messages deleted successfully.';
                break;
                
            case 'mark_read':
                foreach ($messages as $message) {
                    $message->update(['status' => 'read']);
                }
                $message = 'Messages marked as read successfully.';
                break;
                
            case 'mark_replied':
                foreach ($messages as $message) {
                    $message->update(['status' => 'replied']);
                }
                $message = 'Messages marked as replied successfully.';
                break;
                
            case 'mark_archived':
                foreach ($messages as $message) {
                    $message->update(['status' => 'archived']);
                }
                $message = 'Messages archived successfully.';
                break;
        }
        
        return back()->with('success', $message);
    }
} 