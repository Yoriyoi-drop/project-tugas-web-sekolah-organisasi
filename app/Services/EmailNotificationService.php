<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail($user): void
    {
        try {
            SendEmailJob::dispatch(
                $user->email,
                \App\Mail\WelcomeEmail::class,
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'login_url' => route('login'),
                ]
            );

            Log::info('Welcome email queued', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to queue welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($user, $token): void
    {
        try {
            SendEmailJob::dispatch(
                $user->email,
                \App\Mail\PasswordResetEmail::class,
                [
                    'name' => $user->name,
                    'reset_url' => route('password.reset', $token),
                    'token' => $token,
                ]
            );

            Log::info('Password reset email queued', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to queue password reset email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email verification email
     */
    public function sendEmailVerification($user): void
    {
        try {
            SendEmailJob::dispatch(
                $user->email,
                \App\Mail\EmailVerificationMail::class,
                [
                    'name' => $user->name,
                    'verification_url' => route('verification.verify', $user->id),
                ]
            );

            Log::info('Email verification queued', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to queue email verification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send organization invitation email
     */
    public function sendOrganizationInvitation($email, $organization, $inviter): void
    {
        try {
            SendEmailJob::dispatch(
                $email,
                \App\Mail\OrganizationInvitationMail::class,
                [
                    'organization_name' => $organization->name,
                    'inviter_name' => $inviter->name,
                    'invitation_url' => route('organizations.show', $organization),
                ]
            );

            Log::info('Organization invitation queued', [
                'email' => $email,
                'organization_id' => $organization->id,
                'inviter_id' => $inviter->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue organization invitation', [
                'email' => $email,
                'organization_id' => $organization->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send activity notification to organization members
     */
    public function sendActivityNotification($activity, $members): void
    {
        try {
            foreach ($members as $member) {
                if ($member->user) {
                    SendEmailJob::dispatch(
                        $member->user->email,
                        \App\Mail\ActivityNotificationMail::class,
                        [
                            'member_name' => $member->user->name,
                            'activity_title' => $activity->title,
                            'activity_description' => $activity->description,
                            'activity_date' => $activity->date,
                            'organization_name' => $activity->organization->name,
                        ]
                    );
                }
            }

            Log::info('Activity notifications queued', [
                'activity_id' => $activity->id,
                'member_count' => $members->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue activity notifications', [
                'activity_id' => $activity->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send system alert to administrators
     */
    public function sendSystemAlert($subject, $message, $priority = 'normal'): void
    {
        try {
            $admins = \App\Models\User::where('is_admin', true)->get();

            foreach ($admins as $admin) {
                SendEmailJob::dispatch(
                    $admin->email,
                    \App\Mail\SystemAlertMail::class,
                    [
                        'admin_name' => $admin->name,
                        'subject' => $subject,
                        'message' => $message,
                        'priority' => $priority,
                    ]
                );
            }

            Log::info('System alerts queued', [
                'subject' => $subject,
                'admin_count' => $admins->count(),
                'priority' => $priority
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue system alerts', [
                'subject' => $subject,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send report notification
     */
    public function sendReportNotification($email, $reportType, $downloadUrl): void
    {
        try {
            SendEmailJob::dispatch(
                $email,
                \App\Mail\ReportReadyMail::class,
                [
                    'report_type' => $reportType,
                    'download_url' => $downloadUrl,
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ]
            );

            Log::info('Report notification queued', [
                'email' => $email,
                'report_type' => $reportType
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue report notification', [
                'email' => $email,
                'report_type' => $reportType,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send bulk email to multiple recipients
     */
    public function sendBulkEmail($recipients, $emailClass, $data): void
    {
        try {
            $successCount = 0;
            $failureCount = 0;

            foreach ($recipients as $recipient) {
                try {
                    SendEmailJob::dispatch(
                        $recipient['email'],
                        $emailClass,
                        array_merge($data, $recipient)
                    );
                    $successCount++;
                } catch (\Exception $e) {
                    $failureCount++;
                    Log::error('Failed to queue bulk email for recipient', [
                        'email' => $recipient['email'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Bulk email queued', [
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'total_recipients' => count($recipients)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue bulk email', [
                'total_recipients' => count($recipients),
                'error' => $e->getMessage()
            ]);
        }
    }
}
