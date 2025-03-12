<?php

namespace App\Repositories;

use App\Models\Invoice;

class InvoiceRepository
{
    public function getInvoice($id){
        return Invoice::where('id', $id)->first();
    }

    public function getInvoices(){
        return Invoice::latest()->get();
    }

    public function createInvoice($data){
        return Invoice::create($data);
    }

    public function updateInvoice($data, $id){
        return Invoice::where('id', $id)->update($data);
    }

    public function deleteInvoice($id){
        return Invoice::where('id', $id)->delete();
    }

    public function generateInvoiceNumber(){
        $last_invoice = Invoice::latest()->first();
        if($last_invoice){
            $last_invoice_number = $last_invoice->invoice_number;
            $last_invoice_number = explode('-', $last_invoice_number);
            $last_invoice_number = $last_invoice_number[1];
            $last_invoice_number = (int)$last_invoice_number;
            $last_invoice_number++;
            $last_invoice_number = str_pad($last_invoice_number, 6, '0', STR_PAD_LEFT);
            return 'INV-'.$last_invoice_number;
        }else{
            return 'INV-000001';
        }
    }
}
