<?php

namespace Vanguard\Traits;

use Carbon\Carbon;
use Vanguard\UserBankDetail;
use Vanguard\UserDocument;
use Vanguard\UserContractSignature;
use Vanguard\AdminContract;

trait AutoOnboardingTrait
{
    public static function bootAutoOnboardingTrait()
    {
        static::created(function ($user) {
            if (in_array($user->role_id, [1, 7,9])) { // Admin and Manager roles
                $user->onboarding_completed_at = Carbon::now();
                $user->banking_submitted = 1;
                $user->documents_submitted = 1;
                $user->contract_signed = 1;
                $user->onboarding_status = 1;
                $user->save();

                // Create dummy bank details
                UserBankDetail::create([
                    'user_id' => $user->id, // Explicitly set the user_id
                    'bank_id' => 1, // Assuming 1 is a valid bank_id
                    'bank_branch' => 'Auto-generated',
                    'account_name' => $user->first_name . ' ' . $user->last_name,
                    'account_number' => 'AUTO-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                ]);

                // Create dummy documents
                UserDocument::create([
                    'user_id' => $user->id, // Explicitly set the user_id
                    'id_number' => 'AUTO-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'id_photo_path' => 'auto_generated/id_photo.jpg',
                    'kra_pin' => 'AUTO-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'kra_certificate_path' => 'auto_generated/kra_certificate.pdf',
                ]);

                // Create dummy contract signature
                $contract = AdminContract::where('status', 'published')->latest()->first();
                if ($contract) {
                    UserContractSignature::create([
                        'user_id' => $user->id, // Explicitly set the user_id
                        'contract_id' => $contract->id,
                        'signature' => 'Auto-generated Signature',
                        'agreed_at' => Carbon::now(),
                        'status' => 'accepted',
                    ]);
                }
            }
        });
    }
}