<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\EmailSubscriber;
use App\Models\DonationCategory;
use App\Http\Requests\DonationRequest;
use App\Http\Resources\DonationResource;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\TransactionResource;
use App\Notifications\DonationNotification;
use App\Http\Requests\DonationFilterRequest;
use Illuminate\Support\Facades\Notification;

class DonationController extends Controller
{
    public function indexUser(DonationFilterRequest $request)
    {


        $query = Donation::latest();

        // Filter berdasarkan request
        $query->when($request->filled('put_on_highlight'), fn($q) => $q->where('put_on_highlight', $request->put_on_highlight))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('is_active'), fn($q) => $q->where('end_date', '>=', Carbon::now())->where('start_date', '<=', Carbon::now()));


        $paginate = $request->input('paginate');
        $limit = $request->input('limit');

        if ($paginate) {
            $donations = $query->paginate($request->input('per_page', 10));
            $pagination = [
                'total' => $donations->total(),
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'per_page' => $donations->perPage(),
                'from' => $donations->firstItem(),
                'to' => $donations->lastItem(),
            ];
        } else {
            $donations = $limit ? $query->limit($limit)->get() : $query->get();
            $pagination = null;
        }


        return $this->sendSuccess(200, DonationResource::collection($donations), "Donations fetched successfully", $pagination);
    }

    public function indexAdmin(Request $request)
    {
        return view('donations.index');
    }

    public function getDonations(Request $request)
    {
        if ($request->ajax()) {
            $data = Donation::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($donation) {
                    return $donation->category ? $donation->category->name : '-';
                })
                ->editColumn('target_amount', function ($row) {
                    return number_format($row->target_amount, 0, ',', '.');
                })
                ->editColumn('collected_amount', function ($row) {
                    return number_format($row->collected_amount, 0, ',', '.');
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->editColumn('start_date', function ($row) {
                    return Carbon::parse($row->start_date)->format('d M Y H:i');
                })
                ->editColumn('end_date', function ($row) {
                    return Carbon::parse($row->end_date)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('donations.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('donations.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action="' . route('donations.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }


        abort(403);
    }

    public function create()
    {
        $categories = DonationCategory::all();
        return view('donations.donation-form', compact('categories'));
    }

    public function store(DonationRequest $request)
    {

        try {

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            ]);

            $donation = Donation::create([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'fund_usage_details' => $request->fund_usage_details,
                'distribution_information' => $request->distribution_information,
                'target_amount' => $request->target_amount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'location' => $request->location,
                'put_on_highlight' => $request->put_on_highlight,

            ]);

            if ($request->hasFile('image')) {

                $donation->addMedia($request->file('image'))->toMediaCollection('donations');
            }

            if ($request->filled('distribution_information')) {

                $distribution_information = $request->distribution_information;
                $dom = new \DomDocument();
                libxml_use_internal_errors(true);
                $dom->loadHtml($distribution_information, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                $imageFile = $dom->getElementsByTagName('img');


                foreach ($imageFile as $index => $image) {
                    $data = $image->getAttribute('src');

                    if (Str::startsWith($data, 'data:image')) {
                        list($type, $data) = explode(';', $data);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);

                        // Simpan sebagai file sementara
                        $tempFilePath = tempnam(sys_get_temp_dir(), 'donation_img');
                        file_put_contents($tempFilePath, $imageData);

                        // Simpan ke Spatie Media Library
                        $media = $donation->addMedia($tempFilePath)
                            ->usingFileName(time() . $index . '.png')
                            ->toMediaCollection('donations_content');

                        // Ganti URL di Summernote agar sesuai denga    n media yang tersimpan
                        $image->setAttribute('src', $media->getUrl());
                    }
                }

                $donation->distribution_information = $dom->saveHTML();
                $donation->save();
            }

            if ($request->filled('share_via_email')) {

                $users = EmailSubscriber::all();
                Notification::send($users, new DonationNotification($donation));
            }
            return $request->wantsJson()
                ? $this->sendSuccess(201, new DonationResource($donation), "Donation created successfully")
                : redirect()->route('donations.index')->with('success', 'Donation created successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Donation $donation)
    {
        if ($request->wantsJson()) {
            return $this->sendSuccess(200, new DonationResource($donation), "Donation fetched successfully");
        }

        $viewMode = true;
        return view('donations.donation-form', compact('donation', 'viewMode'));
    }

    public function edit(Donation $donation)
    {
        $categories = DonationCategory::all();
        return view('donations.donation-form', compact('donation', 'categories'));
    }

    public function update(DonationRequest $request, Donation $donation)
    {
        try {

            $donation->update([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'fund_usage_details' => $request->fund_usage_details,
                'distribution_information' => $request->distribution_information,
                'target_amount' => $request->target_amount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'location' => $request->location,
                'put_on_highlight' => $request->put_on_highlight,

            ]);

            if ($request->filled('distribution_information')) {
                $dom = new \DomDocument();
                libxml_use_internal_errors(true);
                $dom->loadHtml($request->distribution_information, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                $imageFile = $dom->getElementsByTagName('img');
                $newMediaUrls = []; // Simpan daftar gambar baru

                foreach ($imageFile as $index => $image) {
                    $data = $image->getAttribute('src');

                    // Jika gambar adalah base64 (gambar baru yang diunggah)
                    if (Str::startsWith($data, 'data:image')) {
                        list($type, $data) = explode(';', $data);
                        list(, $data) = explode(',', $data);
                        $imageData = base64_decode($data);

                        // Simpan sebagai file sementara
                        $tempFilePath = tempnam(sys_get_temp_dir(), 'donation_img');
                        file_put_contents($tempFilePath, $imageData);

                        // Simpan ke Media Library
                        $media = $donation->addMedia($tempFilePath)
                            ->usingFileName(time() . $index . '.png')
                            ->toMediaCollection('donations_content');

                        // Ganti URL di Summernote agar sesuai dengan media yang tersimpan
                        $image->setAttribute('src', $media->getUrl());

                        // Simpan URL gambar baru
                        $newMediaUrls[] = $media->getUrl();
                    } else {
                        // Jika gambar sudah ada, tambahkan ke daftar gambar baru
                        $newMediaUrls[] = $data;
                    }
                }

                // Hapus gambar lama yang tidak digunakan lagi
                foreach ($donation->getMedia('donations_content') as $media) {
                    if (!in_array($media->getUrl(), $newMediaUrls)) {
                        $media->delete();
                    }
                }


                $donation->distribution_information = $dom->saveHTML();
                $donation->save();
            }

            if ($request->hasFile('images')) {
                $donation->clearMediaCollection('donations');
                $donation->addMedia($request->file('image'))->toMediaCollection('donations');
            }

            return $request->wantsJson()
                ? $this->sendSuccess(200, new DonationResource($donation), "Donation updated successfully")
                : redirect()->route('donations.index')->with('success', 'Donation updated successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Donation $donation)
    {
        try {

            $donation->clearMediaCollection('donations');
            $donation->delete();

            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "Donation deleted successfully")
                : redirect()->route('donations.index')->with('success', 'Donation deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function transactions(Donation $donation)
    {
        $transactions = $donation->transactions()
            ->where('transaction_status', 'success')
            ->orderByDesc('updated_at')
            ->get();
    
        return $this->sendSuccess(200, TransactionResource::collection($transactions), "Transactions fetched successfully");
    }
}
