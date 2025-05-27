<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Exception;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            try {
                Mail::raw("Message from: {$validated['name']} ({$validated['email']})\n\n{$validated['message']}", function($message) use ($validated) {
                    $message->to(env('MAIL_FROM_ADDRESS', 'wahdan71@gmail.com'));
                    $message->subject("Contact Form: {$validated['subject']}");
                    $message->from(env('MAIL_FROM_ADDRESS', 'no-reply@example.com'), $validated['name']);
                    $message->replyTo($validated['email'], $validated['name']);
                });
                return back()->with('success', 'Thank you for your message! We will get back to you soon.');
            } catch (\Exception  $e) {
                return back()->with('error', 'Mail server connection error: ' . $e->getMessage())->withInput();
            } catch (\Exception $e) {
                return back()->with('error', 'Error sending email: ' . $e->getMessage())->withInput();
            }
        } catch (Exception $e) {



            return back()->with('error', 'Sorry, there was an error processing your request: ' . $e->getMessage())->withInput();
        }
    }
}
