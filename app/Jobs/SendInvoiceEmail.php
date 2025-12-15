<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $invoiceData;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param array $invoiceData
     * @param string $type
     * @return void
     */
    public function __construct($email, $invoiceData, $type)
    {
        $this->email = $email;
        $this->invoiceData = $invoiceData;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new InvoiceMail($this->invoiceData, $this->type));
    }
}
