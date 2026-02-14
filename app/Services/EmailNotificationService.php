<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendEmailJob;
use App\Mail\WelcomeEmail;
use App\Mail\PasswordResetEmail;
use App\Mail\EmailVerificationMail;
use App\Mail\OrganizationInvitationMail;
use App\Mail\ActivityNotificationMail;
use App\Mail\SystemAlertMail;
use App\Mail\ReportReadyMail;

class EmailNotificationService
{
    /**
     * Generic method to send an email
     */
    private function sendEmail($recipient, $emailClass, $emailData, $logContext = []): bool
    {
        try {
            // Validate input
            if (!class_exists($emailClass)) {
                throw new \InvalidArgumentException('Email class does not exist: ' . $emailClass);
            }

            // Validate recipient
            $recipientEmail = null;
            if (is_string($recipient) && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $recipientEmail = $recipient;
            } elseif (is_object($recipient) && isset($recipient->email)) {
                $recipientEmail = $recipient->email;
            } elseif (is_array($recipient) && isset($recipient['email'])) {
                $recipientEmail = $recipient['email'];
            }

            if (!$recipientEmail) {
                throw new \InvalidArgumentException('Valid recipient email is required');
            }

            // Check if email sending is enabled
            if (!$this->isEmailSendingEnabled()) {
                Log::warning('Email sending is disabled', array_merge($logContext, ['email' => $recipientEmail]));
                return false;
            }

            // Send email directly or queue based on configuration
            if ($this->shouldQueueEmails()) {
                SendEmailJob::dispatch($recipientEmail, $emailClass, $emailData);
            } else {
                Mail::to($recipientEmail)->send(new $emailClass($emailData));
            }

            Log::info('Email sent successfully', array_merge($logContext, [
                'recipient' => $recipientEmail,
                'email_class' => $emailClass,
                'queued' => $this->shouldQueueEmails()
            ]));

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send email', array_merge($logContext, [
                'recipient' => $recipientEmail ?? 'unknown',
                'email_class' => $emailClass,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]));

            return false;
        }
    }

    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail($user): void
    {
        if (!$user || !isset($user->email)) {
            Log::error('User must have a valid email address');
            return;
        }

        $emailData = [
            'name' => $user->name,
            'email' => $user->email,
            'login_url' => route('login'),
        ];

        $this->sendEmail($user, WelcomeEmail::class, $emailData, [
            'user_id' => $user->id,
            'email_type' => 'welcome'
        ]);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($user, $token): void
    {
        if (!$user || !isset($user->email) || !$token) {
            Log::error('User and token must be provided');
            return;
        }

        $emailData = [
            'name' => $user->name,
            'reset_url' => route('password.reset', ['token' => $token]),
            'token' => $token,
        ];

        $this->sendEmail($user, PasswordResetEmail::class, $emailData, [
            'user_id' => $user->id,
            'email_type' => 'password_reset'
        ]);
    }

    /**
     * Send email verification email
     */
    public function sendEmailVerification($user): void
    {
        if (!$user || !isset($user->email)) {
            Log::error('User must have a valid email address');
            return;
        }

        $emailData = [
            'name' => $user->name,
            'verification_url' => route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]),
        ];

        $this->sendEmail($user, EmailVerificationMail::class, $emailData, [
            'user_id' => $user->id,
            'email_type' => 'email_verification'
        ]);
    }

    /**
     * Send organization invitation email
     */
    public function sendOrganizationInvitation($email, $organization, $inviter): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::error('Invalid email address', ['provided_email' => $email]);
            return;
        }

        if (!$organization || !$inviter) {
            Log::error('Organization and inviter must be provided');
            return;
        }

        $emailData = [
            'organization_name' => $organization->name,
            'inviter_name' => $inviter->name,
            'invitation_url' => route('organisasi.show', $organization),
        ];

        $this->sendEmail($email, OrganizationInvitationMail::class, $emailData, [
            'email' => $email,
            'organization_id' => $organization->id,
            'inviter_id' => $inviter->id,
            'email_type' => 'organization_invitation'
        ]);
    }

    /**
     * Send activity notification to organization members
     */
    public function sendActivityNotification($activity, $members): void
    {
        if (!$activity || !$members) {
            Log::error('Activity and members must be provided');
            return;
        }

        $processedCount = 0;
        $failedCount = 0;

        foreach ($members as $member) {
            if ($member->user && isset($member->user->email)) {
                $emailData = [
                    'member_name' => $member->user->name,
                    'activity_title' => $activity->title,
                    'activity_description' => $activity->description,
                    'activity_date' => $activity->date,
                    'organization_name' => $activity->organization->name,
                ];

                $success = $this->sendEmail($member->user, ActivityNotificationMail::class, $emailData, [
                    'member_id' => $member->id ?? 'unknown',
                    'activity_id' => $activity->id,
                    'email_type' => 'activity_notification'
                ]);

                if ($success) {
                    $processedCount++;
                } else {
                    $failedCount++;
                }
            }
        }

        Log::info('Activity notifications processed', [
            'activity_id' => $activity->id,
            'processed_count' => $processedCount,
            'failed_count' => $failedCount,
            'total_members' => $members->count() ?? count($members),
            'email_type' => 'activity_notifications_batch'
        ]);
    }

    /**
     * Send system alert to administrators
     */
    public function sendSystemAlert($subject, $message, $priority = 'normal'): void
    {
        if (!$subject || !$message) {
            Log::error('Subject and message must be provided');
            return;
        }

        $admins = User::where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            Log::warning('No admin users found for system alert', ['subject' => $subject]);
            return;
        }

        $processedCount = 0;
        $failedCount = 0;

        foreach ($admins as $admin) {
            $emailData = [
                'admin_name' => $admin->name,
                'subject' => $subject,
                'message' => $message,
                'priority' => $priority,
            ];

            $success = $this->sendEmail($admin, SystemAlertMail::class, $emailData, [
                'admin_id' => $admin->id,
                'subject' => $subject,
                'email_type' => 'system_alert'
            ]);

            if ($success) {
                $processedCount++;
            } else {
                $failedCount++;
            }
        }

        Log::info('System alerts processed', [
            'subject' => $subject,
            'processed_count' => $processedCount,
            'failed_count' => $failedCount,
            'total_admins' => $admins->count(),
            'email_type' => 'system_alerts_batch'
        ]);
    }

    /**
     * Send report notification
     */
    public function sendReportNotification($email, $reportType, $downloadUrl): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$reportType) {
            Log::error('Valid email and report type must be provided', [
                'provided_email' => $email,
                'report_type' => $reportType
            ]);
            return;
        }

        $emailData = [
            'report_type' => $reportType,
            'download_url' => $downloadUrl,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];

        $this->sendEmail($email, ReportReadyMail::class, $emailData, [
            'email' => $email,
            'report_type' => $reportType,
            'email_type' => 'report_notification'
        ]);
    }

    /**
     * Send bulk email to multiple recipients
     */
    public function sendBulkEmail($recipients, $emailClass, $data): void
    {
        if (!is_array($recipients) || empty($recipients)) {
            Log::error('Recipients must be a non-empty array');
            return;
        }

        if (!class_exists($emailClass)) {
            Log::error('Email class does not exist', ['email_class' => $emailClass]);
            return;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($recipients as $recipient) {
            $recipientEmail = $recipient['email'] ?? ($recipient instanceof User ? $recipient->email : null);

            if (!$recipientEmail || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                $failureCount++;
                Log::warning('Invalid email address in bulk recipients', ['recipient' => $recipient]);
                continue;
            }

            $emailData = array_merge($data, $recipient);

            $success = $this->sendEmail($recipientEmail, $emailClass, $emailData, [
                'email_type' => 'bulk_email',
                'recipient_email' => $recipientEmail
            ]);

            if ($success) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        Log::info('Bulk email processed', [
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'total_recipients' => count($recipients),
            'email_type' => 'bulk_emails_batch'
        ]);
    }

    /**
     * Check if email sending is enabled
     */
    private function isEmailSendingEnabled(): bool
    {
        return config('mail.default', 'smtp') !== 'log' || config('app.env') === 'production';
    }

    /**
     * Determine if emails should be queued
     */
    private function shouldQueueEmails(): bool
    {
        return config('queue.default') !== 'sync';
    }
}
