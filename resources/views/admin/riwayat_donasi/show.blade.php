@extends('layouts.app')

@section('title', 'Detail Donasi #' . $donasi->id_donasi)

@section('content')
<style>
/* ═══════════════════════════════════════════
   DESIGN TOKENS — auto dark/light via var()
═══════════════════════════════════════════ */
:root {
    --bg-page      : #f1f5f9;
    --bg-card      : #ffffff;
    --bg-muted     : #f8fafc;
    --border       : #e2e8f0;
    --border-inner : #f1f5f9;
    --text-primary : #0f172a;
    --text-secondary: #475569;
    --text-muted   : #94a3b8;
    --accent       : #059669;
    --accent-dark  : #047857;
    --shadow       : 0 2px 14px rgba(0,0,0,0.06);
    --radius-card  : 18px;
    --radius-sm    : 10px;
}
[data-theme="dark"] {
    --bg-page      : #0f172a;
    --bg-card      : #1e293b;
    --bg-muted     : #273449;
    --border       : #334155;
    --border-inner : #2d3f55;
    --text-primary : #f1f5f9;
    --text-secondary: #94a3b8;
    --text-muted   : #64748b;
    --shadow       : 0 2px 14px rgba(0,0,0,0.3);
}

/* ── Layout ── */
.dv-wrapper { padding: 1.75rem; background: var(--bg-page); min-height: 100%; }

/* ── Back link ── */
.dv-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
    color: var(--text-secondary); text-decoration: none;
    padding: 7px 16px; border-radius: 999px;
    background: var(--bg-card); border: 1px solid var(--border);
    transition: all .15s; margin-bottom: 1.1rem;
}
.dv-back:hover { background: var(--bg-muted); color: var(--text-primary); }

/* ── Page title ── */
.dv-title {
    font-size: 1.55rem; font-weight: 900; font-style: italic;
    text-transform: uppercase; letter-spacing: -.5px;
    color: var(--text-primary); margin: 0; line-height: 1.15;
}
.dv-title span { color: var(--accent); }
.dv-subtitle {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--text-muted); margin-top: 5px;
}

/* ── Status pill — semua kemungkinan nilai ── */
.spill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 16px; border-radius: 999px;
    font-size: .76rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .05em;
}
/* Hijau  : Selesai / Berhasil / berhasil / sukses */
.sp-green   { background: #d1fae5; color: #065f46; }
/* Biru   : Proses / proses / pending */
.sp-blue    { background: #dbeafe; color: #1d4ed8; }
/* Merah  : Ditolak / ditolak / gagal */
.sp-red     { background: #fee2e2; color: #991b1b; }
/* Kuning : Menunggu / menunggu */
.sp-yellow  { background: #fef3c7; color: #92400e; }

/* ── Card ── */
.dv-card {
    background: var(--bg-card);
    border-radius: var(--radius-card);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.dv-card-head {
    display: flex; align-items: center; gap: 9px;
    padding: 13px 20px; border-bottom: 1px solid var(--border-inner);
}
.dv-card-dot { width: 9px; height: 9px; border-radius: 3px; flex-shrink: 0; }
.dv-card-title {
    font-size: .7rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .1em; color: var(--text-secondary);
}
.dv-card-body { padding: 20px; }

/* ── Label / Value ── */
.lbl {
    font-size: .67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: var(--text-muted); margin-bottom: 3px;
}
.val { font-size: .875rem; font-weight: 700; color: var(--text-primary); }

/* ── Stat grid ── */
.stat-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1px; background: var(--border);
    border-radius: var(--radius-sm); overflow: hidden; margin-top: 16px;
}
.stat-cell { background: var(--bg-card); padding: 13px 15px; }
@media(max-width:576px) { .stat-grid { grid-template-columns: 1fr 1fr; } }

/* ── Nominal / Barang box ── */
.nom-box {
    background: linear-gradient(135deg,#ecfdf5,#d1fae5);
    border: 1px solid #a7f3d0; border-radius: 13px; padding: 18px 22px;
}
.nom-amount {
    font-size: 2rem; font-weight: 900; color: #065f46;
    letter-spacing: -1px; line-height: 1; margin: 5px 0 4px;
}
.nom-sub { font-size: .74rem; font-weight: 700; color: #059669; }

.bar-box {
    background: linear-gradient(135deg,#fffbeb,#fef3c7);
    border: 1px solid #fde68a; border-radius: 13px; padding: 18px 22px;
}
.bar-name {
    font-size: 1.5rem; font-weight: 900; color: #78350f;
    letter-spacing: -.5px; line-height: 1.15; margin: 5px 0 4px;
}
.bar-sub { font-size: .74rem; font-weight: 700; color: #b45309; }

/* Dark mode gradient overrides */
[data-theme="dark"] .nom-box { background: linear-gradient(135deg,#064e3b,#065f46); border-color: #047857; }
[data-theme="dark"] .nom-amount { color: #6ee7b7; }
[data-theme="dark"] .nom-sub { color: #34d399; }
[data-theme="dark"] .bar-box { background: linear-gradient(135deg,#451a03,#78350f); border-color: #92400e; }
[data-theme="dark"] .bar-name { color: #fde68a; }
[data-theme="dark"] .bar-sub { color: #fcd34d; }

/* ── Info rows ── */
.info-row {
    padding: 11px 0; border-bottom: 1px solid var(--border-inner);
}
.info-row:last-of-type { border-bottom: none; }

/* ── Donatur avatar ── */
.don-avatar {
    width: 52px; height: 52px; border-radius: 13px;
    background: linear-gradient(135deg,#059669,#047857);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; font-weight: 900; flex-shrink: 0;
}

/* ── Otorisator icon ── */
.otor-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--bg-muted);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.role-badge {
    font-size: .7rem; font-weight: 800; letter-spacing: .06em;
    background: var(--text-primary); color: var(--bg-card);
    padding: 5px 13px; border-radius: 999px;
}

/* ── WhatsApp button ── */
.btn-wa {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    background: #25d366; color: #fff; border: none;
    border-radius: var(--radius-sm); padding: 11px 20px;
    font-size: .82rem; font-weight: 800; text-decoration: none;
    width: 100%; transition: background .15s; margin-top: 18px;
}
.btn-wa:hover { background: #1ebe5d; color: #fff; }
.btn-wa-off {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    background: var(--bg-muted); color: var(--text-muted);
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    padding: 11px 20px; font-size: .82rem; font-weight: 800;
    width: 100%; cursor: not-allowed; margin-top: 18px;
}

/* ── Status select ── */
.status-select {
    background: var(--bg-muted); border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); padding: 10px 14px;
    font-size: .84rem; font-weight: 700; color: var(--text-primary);
    width: 100%; appearance: auto;
}

/* ── Save button ── */
.btn-save {
    background: var(--accent); color: #fff; border: none;
    border-radius: var(--radius-sm); padding: 12px 20px;
    font-size: .82rem; font-weight: 800; width: 100%;
    transition: background .15s; cursor: pointer;
}
.btn-save:hover { background: var(--accent-dark); }

/* ── Alert ── */
.dv-alert {
    display: flex; align-items: center; gap: 10px;
    background: var(--accent); color: #fff;
    border-radius: var(--radius-sm); padding: 12px 18px;
    font-size: .84rem; font-weight: 700; margin-bottom: 1.25rem;
}
</style>

<div class="dv-wrapper">

    {{-- ── Back + Header ── --}}
    <a href="{{ route('admin.riwayat_donasi.index') }}" class="dv-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Induk
    </a>

    @php
        $st = $donasi->status_donasi;
        $stLower = strtolower($st);

        // Tentukan class & icon berdasarkan semua kemungkinan nilai status
        if (in_array($stLower, ['selesai', 'berhasil', 'sukses', 'success', 'diterima'])) {
            $spClass = 'sp-green';
            $spIcon  = '✓';
        } elseif (in_array($stLower, ['proses', 'pending', 'menunggu', 'diproses', 'review'])) {
            $spClass = 'sp-blue';
            $spIcon  = '⏳';
        } elseif (in_array($stLower, ['ditolak', 'gagal', 'batal', 'dibatalkan', 'failed'])) {
            $spClass = 'sp-red';
            $spIcon  = '✕';
        } else {
            // fallback: kuning untuk status tidak dikenal
            $spClass = 'sp-yellow';
            $spIcon  = '◌';
        }
    @endphp

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <h1 class="dv-title">DETAIL <span>TRANSAKSI DONASI</span> #{{ $donasi->id_donasi }}</h1>
            <p class="dv-subtitle">Otorisasi, verifikasi, dan monitoring berkas donasi secara terpusat</p>
        </div>
        <span class="spill {{ $spClass }}">{{ $spIcon }} {{ $st }}</span>
    </div>

    @if(session('success'))
        <div class="dv-alert">
            <i class="fas fa-check-circle fs-5"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">

        {{-- ══════════ KIRI ══════════ --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-4">

            {{-- Card: Komoditas Donasi --}}
            <div class="dv-card">
                <div class="dv-card-head">
                    <div class="dv-card-dot" style="background:var(--accent);"></div>
                    <span class="dv-card-title">Komoditas Donasi</span>
                </div>
                <div class="dv-card-body">

                    @if($donasi->donasi_uang)
                        <div class="nom-box">
                            <div class="lbl" style="color:#059669;">Total Nominal Uang Tunai</div>
                            <div class="nom-amount">
                                Rp {{ number_format($donasi->donasi_uang->nominal, 0, ',', '.') }}
                            </div>
                            <div class="nom-sub">✓ Donasi Berhasil Terkirim</div>
                        </div>
                        <div class="stat-grid">
                            <div class="stat-cell">
                                <div class="lbl">ID Saluran Bayar</div>
                                <div class="val">{{ $donasi->donasi_uang->pembayaran->id_pembayaran ?? 'Manual Admin' }}</div>
                            </div>
                            <div class="stat-cell">
                                <div class="lbl">Metode Bayar</div>
                                <div class="val text-uppercase">{{ $donasi->donasi_uang->pembayaran->metode_pembayaran ?? 'Tunai' }}</div>
                            </div>
                            <div class="stat-cell">
                                <div class="lbl">Status Gateway</div>
                                <div class="val" style="color:#059669;">SUCCESS</div>
                            </div>
                        </div>

                    @elseif($donasi->donasi_barang)
                        <div class="bar-box">
                            <div class="lbl" style="color:#92400e;">Logistik / Berwujud Barang</div>
                            <div class="bar-name">{{ $donasi->donasi_barang->nama_barang ?? '-' }}</div>
                            <div class="bar-sub">
                                {{ $donasi->donasi_barang->jumlah_barang ?? $donasi->donasi_barang->jumlah ?? '-' }}
                                {{ $donasi->donasi_barang->satuan_barang ?? 'Unit' }}
                            </div>
                        </div>
                        <div class="stat-grid">
                            <div class="stat-cell">
                                <div class="lbl">Kategori</div>
                                <div class="val">
                                    {{ optional(optional($donasi->donasi_barang)->kategoriBarang)->nama_kategori
                                       ?? optional(optional($donasi->donasi_barang)->kategori_barang)->nama_kategori
                                       ?? 'Umum' }}
                                </div>
                            </div>
                            <div class="stat-cell" style="grid-column:span 2;">
                                <div class="lbl">Keterangan</div>
                                <div class="val">{{ $donasi->donasi_barang->keterangan ?? 'Tidak ada catatan tambahan' }}</div>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-5" style="color:var(--text-muted);">
                            <i class="fas fa-inbox fa-2x mb-2 opacity-25 d-block"></i>
                            Tidak ada data komoditas donasi.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card: Informasi Kunjungan --}}
            <div class="dv-card">
                <div class="dv-card-head">
                    <div class="dv-card-dot" style="background:#6366f1;"></div>
                    <span class="dv-card-title">Informasi Berkas Kunjungan</span>
                </div>
                <div class="dv-card-body">
                    <div class="stat-grid">
                        <div class="stat-cell">
                            <div class="lbl">ID Kunjungan</div>
                            <div class="val">#{{ $donasi->id_kunjungan ?? '-' }}</div>
                        </div>
                        <div class="stat-cell">
                            <div class="lbl">Tanggal Kedatangan</div>
                            <div class="val">
                                @if($donasi->kunjungan && $donasi->kunjungan->tgl_kunjungan)
                                    {{ \Carbon\Carbon::parse($donasi->kunjungan->tgl_kunjungan)->translatedFormat('d F Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($donasi->created_at)->translatedFormat('d F Y') }}
                                @endif
                            </div>
                        </div>
                        <div class="stat-cell">
                            <div class="lbl">Skor Kepuasan SOWAN</div>
                            @if($donasi->kunjungan && $donasi->kunjungan->penilaian)
                                <div class="val" style="color:#f59e0b;">
                                    <i class="fas fa-star me-1"></i>{{ $donasi->kunjungan->penilaian->skor_rating ?? '0' }} / 5
                                </div>
                            @else
                                <div class="val" style="color:var(--text-muted);font-weight:600;">Belum mengisi</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Otorisator --}}
            <div class="dv-card">
                <div class="dv-card-head">
                    <div class="dv-card-dot" style="background:#f59e0b;"></div>
                    <span class="dv-card-title">Otorisator Terakhir</span>
                </div>
                <div class="dv-card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="otor-icon">
                            <i class="fas fa-user-shield" style="color:var(--text-secondary);"></i>
                        </div>
                        <div>
                            <div class="lbl">Nama Otorisator</div>
                            <div class="val">{{ $donasi->user->nama_user ?? 'Sistem Otomatis / Belum Ada' }}</div>
                        </div>
                    </div>
                    <span class="role-badge">{{ strtoupper($donasi->user->role ?? 'NONE') }}</span>
                </div>
            </div>
        </div>

        {{-- ══════════ KANAN ══════════ --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            {{-- Card: Profil Donatur --}}
            <div class="dv-card">
                <div class="dv-card-head">
                    <div class="dv-card-dot" style="background:var(--accent);"></div>
                    <span class="dv-card-title">Profil Donatur</span>
                </div>
                <div class="dv-card-body">
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-3"
                         style="background:var(--bg-muted); border:1px solid var(--border-inner);">
                        <div class="don-avatar">
                            {{ strtoupper(substr($donasi->kunjungan->donatur->nama_donatur ?? 'AN', 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:.95rem;font-weight:900;font-style:italic;text-transform:uppercase;color:var(--text-primary);line-height:1.2;">
                                {{ $donasi->kunjungan->donatur->nama_donatur ?? 'Anonim / Umum' }}
                            </div>
                            <div style="font-size:.68rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.05em;margin-top:2px;">
                                Entitas Pemberi Hibah
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="lbl"><i class="fas fa-phone-alt me-1"></i>Nomor Handphone</div>
                        <div class="val">{{ $donasi->kunjungan->donatur->no_hp ?? 'Tidak Mengisi' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="lbl"><i class="fas fa-map-marker-alt me-1"></i>Alamat</div>
                        <div class="val">{{ $donasi->kunjungan->donatur->alamat ?? 'Tidak Mengisi' }}</div>
                    </div>

                    @if($donasi->kunjungan && $donasi->kunjungan->donatur && $donasi->kunjungan->donatur->no_hp)
                        @php
                            $cp = preg_replace('/[^0-9]/', '', $donasi->kunjungan->donatur->no_hp);
                            if (str_starts_with($cp, '0')) $cp = '62' . substr($cp, 1);
                            $wm = urlencode(
                                "Halo Bapak/Ibu " . $donasi->kunjungan->donatur->nama_donatur .
                                ", Kami dari pihak pengurus Yayasan ingin mengonfirmasi amanah donasi Anda dengan ID Transaksi #" .
                                $donasi->id_donasi . " yang telah kami terima. Terima kasih."
                            );
                        @endphp
                        <a href="https://wa.me/{{ $cp }}?text={{ $wm }}" target="_blank" class="btn-wa">
                            <i class="fab fa-whatsapp fs-5"></i> Hubungi via WhatsApp
                        </a>
                    @else
                        <div class="btn-wa-off">
                            <i class="fab fa-whatsapp"></i> Kontak Tidak Tersedia
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card: Otorisasi Status --}}
            <div class="dv-card">
                <div class="dv-card-head">
                    <div class="dv-card-dot" style="background:#f43f5e;"></div>
                    <span class="dv-card-title">Otorisasi Status</span>
                </div>
                <div class="dv-card-body">
                    <form action="{{ route('admin.riwayat_donasi.update_status', $donasi->id_donasi) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <div class="lbl mb-2">Ubah Status Riwayat</div>
                            <select name="status_donasi" class="status-select">
                                <option value="Proses"   {{ $donasi->status_donasi === 'Proses'   ? 'selected' : '' }}>⏳  Proses</option>
                                <option value="Selesai"  {{ $donasi->status_donasi === 'Selesai'  ? 'selected' : '' }}>✓  Selesai</option>
                                <option value="Berhasil" {{ $donasi->status_donasi === 'Berhasil' ? 'selected' : '' }}>✓  Berhasil</option>
                                <option value="Ditolak"  {{ $donasi->status_donasi === 'Ditolak'  ? 'selected' : '' }}>✕  Ditolak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan Status
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    function applyTheme() {
        const body = document.body;
        const html = document.documentElement;
        const isDark =
            body.classList.contains('dark') ||
            body.classList.contains('dark-mode') ||
            html.getAttribute('data-theme') === 'dark' ||
            html.classList.contains('dark');
        html.setAttribute('data-theme', isDark ? 'dark' : 'light');
    }
    applyTheme();
    const obs = new MutationObserver(applyTheme);
    obs.observe(document.body, { attributes: true, attributeFilter: ['class','data-theme'] });
    obs.observe(document.documentElement, { attributes: true, attributeFilter: ['class','data-theme'] });
})();
</script>
@endsection