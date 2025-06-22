<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('user');

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhere('gst_number', 'like', '%' . $request->search . '%')
                ->orWhere('total_amount', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'invoice_date');
        $sortDir = $request->get('direction', 'desc');

        $allowedSorts = ['invoice_number', 'invoice_date', 'due_date', 'total_amount'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'invoice_date';
        }

        // Date filter        
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('invoice_date', [$request->from_date, $request->to_date]);
        }

        $invoices = $query
            ->where('user_id', Auth::id())
            ->orderBy($sortField, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Billing/Index', [
            'invoices' => $invoices,
            'filters' => $request->only(['search', 'from_date', 'to_date']),
            'sort' => $sortField,
            'direction' => $sortDir,
        ]);
    }

    // Used for invoice details popup
    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'items']); 
        return response()->json($invoice);
    }

    // Download invoice pdf
    public function download(Invoice $invoice)
    {
        $invoice->load(['user', 'items']);

        $data = [
            'invoice' => $invoice,
            'company' => [
                'name' => env('COMPANY_NAME', 'My CRM'),
                'address' => env('COMPANY_ADDRESS', '123 Main Street, City'),
                'contact' => env('COMPANY_CONTACT', 'contact@crm.com'),
            ],
            'user' => Auth::user(),
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        return $pdf->download("invoice_{$invoice->invoice_number}.pdf");
    }
}
