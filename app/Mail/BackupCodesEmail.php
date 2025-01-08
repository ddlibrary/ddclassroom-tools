<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupCodesEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $backupCodes;

    public function __construct(array $backupCodes)
    {
        $this->backupCodes = $backupCodes;
    }

    public function build()
    {
        return $this->subject('Your Two-Factor Authentication Backup Codes')
            ->markdown('emails.backup-codes');
    }
}
