<?php
namespace App\Services;

use App\Models\Contact;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactFormSubmitted as ContactNotification;

class ContactService
{
    public function submitContactForm(array $data)
    {
        // Validate input data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:500',
            'message' => 'required|string|min:10|max:2000',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Create contact record
        $contact = Contact::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'phone' => $data['phone'] ?? null,
            'organization' => $data['organization'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send notification to admin
        $adminUsers = \App\Models\User::where('is_admin', true)->get();
        Notification::send($adminUsers, new ContactNotification($contact));

        // Optionally send email confirmation to user
        if (isset($data['send_confirmation']) && $data['send_confirmation']) {
            Mail::to($contact->email)->send(new ContactFormMail($contact, 'confirmation'));
        }

        // Send email to admin
        $adminEmail = config('mail.from.address', 'admin@manu.com');
        Mail::to($adminEmail)->send(new ContactFormMail($contact, 'admin_notification'));

        return [
            'success' => true,
            'message' => 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.',
            'contact' => $contact
        ];
    }

    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return $contact;
    }

    public function markAsReplied(Contact $contact)
    {
        $contact->update(['is_replied' => true]);
        return $contact;
    }

    public function getUnreadCount()
    {
        return Contact::where('is_read', false)->count();
    }

    public function getUnrepliedCount()
    {
        return Contact::where('is_replied', false)->count();
    }

    public function getRecentContacts($limit = 10)
    {
        return Contact::latest()->limit($limit)->get();
    }

    public function searchContacts($query, $filters = [])
    {
        $contacts = Contact::query();

        if (!empty($query)) {
            $contacts->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('subject', 'LIKE', "%{$query}%")
                  ->orWhere('message', 'LIKE', "%{$query}%");
            });
        }

        if (!empty($filters['status'])) {
            switch ($filters['status']) {
                case 'read':
                    $contacts->where('is_read', true);
                    break;
                case 'unread':
                    $contacts->where('is_read', false);
                    break;
                case 'replied':
                    $contacts->where('is_replied', true);
                    break;
                case 'unreplied':
                    $contacts->where('is_replied', false);
                    break;
            }
        }

        if (!empty($filters['date_from'])) {
            $contacts->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $contacts->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $contacts->latest()->paginate(15);
    }
}
