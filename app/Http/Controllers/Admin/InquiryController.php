<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::latest();

        if ($request->filled('status') && in_array($request->status, ['new', 'contacted', 'completed'])) {
            $query->where('status', $request->status);
        }

        $inquiries   = $query->paginate(20)->withQueryString();
        $newCount    = Inquiry::where('status', 'new')->count();
        $activeStatus = $request->input('status', 'all');

        return view('admin.inquiries.index', compact('inquiries', 'newCount', 'activeStatus'));
    }

    public function show(Inquiry $inquiry)
    {
        $whatsapp = SiteSetting::get('whatsapp_number');
        return view('admin.inquiries.show', compact('inquiry', 'whatsapp'));
    }

    public function markStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,completed',
        ]);

        $inquiry->update(['status' => $request->status]);

        return redirect()->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Status updated to ' . ucfirst($request->status) . '.');
    }
}
