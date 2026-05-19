<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Referral;
use App\Models\Kelas;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email',
            'no_hp' => 'required',
            'kelas_id' => 'required',
            'nama_kelas' => 'required|string',
            'referral_kode' => 'nullable|string'
        ]);

        // =============================
        // 🔒 FORMAT NOMOR WA
        // =============================
        $no_hp = preg_replace('/^0/', '62', $request->no_hp);

        // =============================
        // 🔒 CEK SUDAH BELI (BELUM SELESAI)
        // =============================
        $existing = Order::where('email', $request->email)
            ->where('kelas_id', $request->kelas_id)
            ->where('status_kelas', 0)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas masih aktif, tidak bisa beli lagi.'
            ], 422);
        }

        // =============================
        // 🔥 AMBIL HARGA ASLI (ANTI MANIPULASI)
        // =============================
        $kelas = Kelas::findOrFail($request->kelas_id);

        $originalAmount = (int) $kelas->harga;
        $finalAmount = $originalAmount;

        $discPercent = 0;
        $referralKode = null;

        // =============================
        // 💸 HITUNG DISKON (SERVER ONLY)
        // =============================
        if ($request->referral_kode) {
            $ref = Referral::where('kode', $request->referral_kode)->first();

            if ($ref && $ref->disc > 0) {
                $discPercent = (int) $ref->disc;

                $potongan = ($discPercent / 100) * $originalAmount;
                $finalAmount = (int) round($originalAmount - $potongan);

                $referralKode = $ref->kode;
            }
        }

        // =============================
        // 💾 SIMPAN ORDER
        // =============================
        $order = Order::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $no_hp,
            'kelas_id' => $request->kelas_id,
            'nama_kelas' => $request->nama_kelas,
            'amount' => $finalAmount,
            'status' => 'pending',
            'status_kelas' => 0,
            'referral_kode' => $referralKode,
            'disc' => $discPercent
        ]);

        // =============================
        // 💳 XENDIT INVOICE
        // =============================
        $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
            ->post('https://api.xendit.co/v2/invoices', [
                'external_id' => 'ORDER-' . $order->id,
                'amount' => $finalAmount,
                'payer_email' => $request->email,
                'description' => 'Pembelian ' . $request->nama_kelas,

                // 🔥 penting
                'should_send_email' => true,

                // 🔥 redirect
                'success_redirect_url' => url('/payment/success'),
                'failure_redirect_url' => url('/payment/failed'),
            ]);

        $invoice = $response->json();

        if (!$response->successful() || !isset($invoice['invoice_url'])) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat invoice',
                'debug' => $invoice
            ], 500);
        }

        // =============================
        // 🔄 UPDATE ORDER
        // =============================
        $order->update([
            'invoice_id' => $invoice['id'] ?? null,
            'payment_url' => $invoice['invoice_url'] ?? null,
        ]);

        // =============================
        // 📲 OPTIONAL: KIRIM WHATSAPP
        // =============================
        if (env('FONNTE_TOKEN')) {
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target' => $no_hp,
                'message' => "Halo {$request->nama},

Pesanan kamu berhasil dibuat 🎉

📚 Kelas: {$request->nama_kelas}
💰 Total: Rp " . number_format($finalAmount, 0, ',', '.') . "

Silakan lanjut pembayaran:
{$invoice['invoice_url']}

Terima kasih 🙌"
            ]);
        }

        return response()->json([
            'status' => true,
            'url' => $invoice['invoice_url']
        ]);
    }
}
