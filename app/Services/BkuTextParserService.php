<?php

namespace App\Services;

use Carbon\Carbon;

class BkuTextParserService
{
    public function parse(string $text): array
    {
        $text = preg_replace('/\s+/', ' ', $text);

        return [
            'metadata' => $this->parseMetadata($text),
            'saldo_awal' => $this->parseSaldoAwal($text),
            'transaksi' => $this->parseTransaksi($text),
            'total' => $this->parseTotal($text),
        ];
    }

    /* ================= METADATA ================= */

    protected function parseMetadata(string $text): array
    {
        preg_match('/SKPD:\s([\d\.]+)\s-\s(.+?)\sTAHUN ANGGARAN:\s(\d{4})/i', $text, $m);
        preg_match('/Periode:\s(.+?)\ss\.d\s(.+?)\sTanggal/i', $text, $p);

        return [
            'skpd_kode' => $m[1] ?? null,
            'skpd_nama' => $m[2] ?? null,
            'tahun_anggaran' => $m[3] ?? null,
            'periode' => [
                'mulai' => $this->normalizeDate($p[1] ?? null),
                'sampai' => $this->normalizeDate($p[2] ?? null),
            ],
        ];
    }

    /* ================= SALDO AWAL ================= */

    protected function parseSaldoAwal(string $text): array
    {
        preg_match('/Saldo Sebelumnya\sRp([\d\.,]+)\sRp([\d\.,]+)\sRp([\d\.,]+)/i', $text, $m);

        return [
            'penerimaan' => $this->toInt($m[1] ?? 0),
            'pengeluaran' => $this->toInt($m[2] ?? 0),
            'saldo' => $this->toInt($m[3] ?? 0),
        ];
    }

    /* ================= TRANSAKSI ================= */

    protected function parseTransaksi(string $text): array
    {
        $text = preg_replace('/\s+/', ' ', $text); // rapikan spasi

        $pattern = '/
    (\d{2}\s+[A-Za-z]+\s+\d{4})      # tanggal
    (.*?)                            # isi transaksi
    (Rp[\d\.,]+)                     # penerimaan
    \s*
    (Rp[\d\.,]+)                     # pengeluaran
    \s*
    (Rp[\d\.,]+)                     # saldo
/ix';

        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $rows = [];
        $isFirst = true;
        foreach ($matches as $m) {
            if ($isFirst) {
                // data pertama: saldo awal
                $rows[] = [
                    'tanggal' => null,
                    'nomor_bukti' => null,
                    'uraian' => 'Saldo sebelumnya',
                    'penerimaan' => $this->toInt($m[3]),
                    'pengeluaran' => $this->toInt($m[4]),
                    'saldo' => $this->toInt($m[5]),
                ];
                $isFirst = false;
            } else {
                // ambil nomor bukti sampai tanggal dalam kurung
                preg_match('/\d[\d\.\/A-Z]+(?:\s*\(\d{2}\/[A-Za-z]+\/\d{4}\))/', $m[2], $noBukti);
                $nomorBukti = trim($noBukti[0] ?? null);

                // hapus nomor bukti dari uraian
                $uraian = trim(preg_replace('/\d[\d\.\/A-Z]+(?:\s*\(\d{2}\/[A-Za-z]+\/\d{4}\))/', '', $m[2]));

                $rows[] = [
                    'tanggal' => $this->normalizeDate($m[1]),
                    'nomor_bukti' => $nomorBukti,
                    'uraian' => $uraian,
                    'penerimaan' => $this->toInt($m[3]),
                    'pengeluaran' => $this->toInt($m[4]),
                    'saldo' => $this->toInt($m[5]),
                ];
            }
        }

        return $rows;
    }

    /* ================= TOTAL ================= */

    protected function parseTotal(string $text): array
    {
        preg_match('/Total\sRp([\d\.,]+)\sRp([\d\.,]+)\sRp([\d\.,]+)/i', $text, $m);

        return [
            'penerimaan' => $this->toInt($m[1] ?? 0),
            'pengeluaran' => $this->toInt($m[2] ?? 0),
            'saldo_akhir' => $this->toInt($m[3] ?? 0),
        ];
    }

    /* ================= HELPER ================= */

    protected function toInt($value): int
    {
        $cleaned = str_replace(
            ['Rp', '.', ',', ' '],
            ['', '', '', ''],
            $value
        );

        // hapus 2 angka paling belakang
        $trimmed = substr($cleaned, 0, -2);

        return (int) $trimmed;
    }

    protected function normalizeDate(?string $date): ?string
    {
        if (!$date) return null;

        // Mapping Indonesian month names to English
        $indoMonths = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        // Replace Indonesian month names with English
        $date = str_replace(array_keys($indoMonths), array_values($indoMonths), $date);

        try {
            return Carbon::createFromFormat('d F Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
