<?php

namespace App\Http\Controllers;

use App\Models\Bku;
use App\Models\BkuDetail;
use App\Models\Skpd;
use Illuminate\Http\Request;
use App\Services\BkuTextParserService;

class BkuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bkus = Bku::with('skpd')->latest()->paginate(10);
        return view('superadmin.bku.index', compact('bkus'));
    }

    /**
     * Get BKU details as JSON for modal display.
     */
    public function getDetails($id)
    {
        $bku = Bku::with('skpd')->find($id);
        
        if (!$bku) {
            return response()->json([
                'success' => false,
                'message' => 'BKU tidak ditemukan'
            ], 404);
        }
        
        $details = BkuDetail::where('bku_id', $id)
            ->orderBy('id')
            ->get();
        
        return response()->json([
            'success' => true,
            'bku' => [
                'id' => $bku->id,
                'skpd' => $bku->skpd ? $bku->skpd->nama_skpd : '-',
                'bulan' => $bku->bulan,
                'tahun' => $bku->tahun
            ],
            'details' => $details
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skpds = Skpd::all();
        return view('superadmin.bku.create', compact('skpds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Trim and clean the kode_skpd input to remove whitespace and carriage returns
        $request->merge([
            'kode_skpd' => trim($request->input('kode_skpd')),
        ]);

        $validated = $request->validate([
            'kode_skpd' => 'required|exists:skpd,kode_skpd',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        // Check if BKU with the same kode_skpd, bulan, and tahun already exists
        $existingBku = Bku::where('kode_skpd', $validated['kode_skpd'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->first();

        if ($existingBku) {
            return redirect()
                ->route('upload.bku.create')
                ->with('error', 'Data BKU untuk SKPD, bulan, dan tahun tersebut sudah ada.')
                ->withInput();
        }

        Bku::create($validated);

        return redirect()->route('upload.bku.index')
            ->with('success', 'Data BKU berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bku = Bku::with('skpd')->findOrFail($id);
        return view('superadmin.bku.show', compact('bku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bku = Bku::findOrFail($id);
        $skpds = Skpd::all();
        return view('superadmin.bku.edit', compact('bku', 'skpds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bku = Bku::findOrFail($id);

        // Trim and clean the kode_skpd input to remove whitespace and carriage returns
        $request->merge([
            'kode_skpd' => trim($request->input('kode_skpd')),
        ]);

        $validated = $request->validate([
            'kode_skpd' => 'required|exists:skpd,kode_skpd',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        // Check if BKU with the same kode_skpd, bulan, and tahun already exists (excluding current BKU)
        $existingBku = Bku::where('kode_skpd', $validated['kode_skpd'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingBku) {
            return redirect()
                ->route('upload.bku.edit', $id)
                ->with('error', 'Data BKU untuk SKPD, bulan, dan tahun tersebut sudah ada.')
                ->withInput();
        }

        $bku->update($validated);

        return redirect()->route('upload.bku.index')
            ->with('success', 'Data BKU berhasil diperbarui.');
    }

    /**
     * Display the OCR page.
     */
    public function ocr(Request $request)
    {
        $selectedBkuId = $request->input('bku_id');
        
        if (!$selectedBkuId) {
            return redirect()
                ->route('upload.bku.index')
                ->with('error', 'Silakan pilih BKU terlebih dahulu dari daftar BKU.');
        }
        
        $selectedBku = Bku::with('skpd')->find($selectedBkuId);
        
        if (!$selectedBku) {
            return redirect()
                ->route('upload.bku.index')
                ->with('error', 'BKU yang dipilih tidak ditemukan.');
        }

        return view('superadmin.bku.ocr', compact('selectedBkuId', 'selectedBku'));
    }

    /**
     * Process OCR on uploaded PDF file using Z AI API.
     */
    public function ocrProcess(Request $request)
    {
        $validated = $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:10240',
            'bku_id' => 'nullable|integer|exists:bku,id',
        ]);

        $file = $request->file('pdf_file');

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file->getPathname());
        $text = $pdf->getText();

        // $text = preg_replace('/\s+/', ' ', $text);

        $parser = new BkuTextParserService();
        $data = $parser->parse($text);

        $bkuId = $request->input('bku_id');

        return redirect()
            ->route('upload.bku.ocr', ['bku_id' => $bkuId])
            ->with('success', 'PDF berhasil diproses dan data berhasil diekstrak!')
            ->with('extractedData', $data['transaksi'] ?? [])
            ->with('bku_id', $bkuId);
    }

    /**
     * Save extracted OCR data to BkuDetail model.
     */
    public function ocrSave(Request $request)
    {
        $extractedData = $request->input('extractedData');
        $bkuId = $request->input('bku_id');

        if (empty($extractedData)) {
            return redirect()
                ->route('upload.bku.ocr')
                ->with('error', 'Tidak ada data untuk disimpan.');
        }

        // Decode JSON data
        $transactions = json_decode($extractedData, true);
        if (!is_array($transactions)) {
            return redirect()
                ->route('upload.bku.ocr')
                ->with('error', 'Format data tidak valid.');
        }

        // Validate BKU ID
        $bku = Bku::find($bkuId);
        if (!$bku) {
            return redirect()
                ->route('upload.bku.ocr')
                ->with('error', 'BKU yang dipilih tidak ditemukan.');
        }

        // Delete existing BkuDetail records for this BKU
        $deletedCount = BkuDetail::where('bku_id', $bkuId)->delete();

        // Save each transaction as BkuDetail
        $savedCount = 0;
        foreach ($transactions as $transaction) {
            BkuDetail::create([
                'bku_id' => $bku->id,
                'tanggal' => $transaction['tanggal'] ?? null,
                'nomor_bukti' => $transaction['nomor_bukti'] ?? null,
                'uraian' => $transaction['uraian'] ?? null,
                'penerimaan' => $transaction['penerimaan'] ?? 0,
                'pengeluaran' => $transaction['pengeluaran'] ?? 0,
                'saldo' => $transaction['saldo'] ?? 0,
                'status_ocr' => 'Jelas',
            ]);
            $savedCount++;
        }

        return redirect()
            ->route('upload.bku.ocr', ['bku_id' => $bkuId])
            ->with('success', "Berhasil menyimpan {$savedCount} data transaksi ke database ({$deletedCount} data lama dihapus).");
    }

    /**
     * Extract BKU data using Z AI API.
     */
    private function extractBkuDataWithZAI($pdfText)
    {
        $apiKey = env('ZAI_API_KEY');
        $apiUrl = 'https://api.z.ai/api/paas/v4/chat/completions';

        // Prepare the prompt for Z AI
        $prompt = "Extract BKU (Buku Kas Umum) data from the following PDF text content. 

Return the data in JSON format with the following structure:
[
  {
    \"tanggal\": \"DD/MM/YYYY\",
    \"uraian\": \"description\",
    \"penerimaan\": \"Rp X.XXX.XXX\" or \"-\",
    \"pengeluaran\": \"Rp X.XXX.XXX\" or \"-\",
    \"saldo\": \"Rp X.XXX.XXX\"
  }
]

Only return the JSON array, no other text. Extract all transaction rows from the BKU table.

Here is the PDF text content:
" . $pdfText;

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($apiUrl, [
                'model' => 'glm-4.7',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
                'max_output_tokens' => 4000,
            ]);

            if (!$response->successful()) {
                \Log::error('Z AI API Error: ' . $response->body());
                throw new \Exception('Z AI API request failed: ' . $response->status());
            }

            $result = $response->json();

            if (!isset($result['choices'][0]['message']['content'])) {
                \Log::error('Z AI API unexpected response: ' . json_encode($result));
                throw new \Exception('Invalid response from Z AI API');
            }

            $content = $result['choices'][0]['message']['content'];
            \Log::info('Z AI Response: ' . substr($content, 0, 500) . '...');

            // Extract JSON from the response
            if (preg_match('/\[[\s\S]*\]/', $content, $matches)) {
                $data = json_decode($matches[0], true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    return $data;
                }
            }

            \Log::warning('Could not parse Z AI response as JSON');
            return [];
        } catch (\Exception $e) {
            \Log::error('Z AI API Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get sample BKU data for demonstration.
     */
    private function getSampleData()
    {
        return [
            [
                'tanggal' => '01/02/2026',
                'uraian' => 'Saldo Awal',
                'penerimaan' => 'Rp 150.000.000',
                'pengeluaran' => '-',
                'saldo' => 'Rp 150.000.000'
            ],
            [
                'tanggal' => '05/02/2026',
                'uraian' => 'Penerimaan Dana Transfer',
                'penerimaan' => 'Rp 50.000.000',
                'pengeluaran' => '-',
                'saldo' => 'Rp 200.000.000'
            ],
            [
                'tanggal' => '10/02/2026',
                'uraian' => 'Pembelian ATK Kantor',
                'penerimaan' => '-',
                'pengeluaran' => 'Rp 5.000.000',
                'saldo' => 'Rp 195.000.000'
            ],
            [
                'tanggal' => '15/02/2026',
                'uraian' => 'Biaya Perjalanan Dinas',
                'penerimaan' => '-',
                'pengeluaran' => 'Rp 10.000.000',
                'saldo' => 'Rp 185.000.000'
            ],
            [
                'tanggal' => '20/02/2026',
                'uraian' => 'Pembayaran Listrik',
                'penerimaan' => '-',
                'pengeluaran' => 'Rp 3.500.000',
                'saldo' => 'Rp 181.500.000'
            ],
            [
                'tanggal' => '25/02/2026',
                'uraian' => 'Pembayaran Internet',
                'penerimaan' => '-',
                'pengeluaran' => 'Rp 2.000.000',
                'saldo' => 'Rp 179.500.000'
            ],
            [
                'tanggal' => '28/02/2026',
                'uraian' => 'Saldo Akhir',
                'penerimaan' => '-',
                'pengeluaran' => '-',
                'saldo' => 'Rp 179.500.000'
            ]
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bku = Bku::findOrFail($id);
        $bku->delete();

        return redirect()->route('upload.bku.index')
            ->with('success', 'Data BKU berhasil dihapus.');
    }
}
