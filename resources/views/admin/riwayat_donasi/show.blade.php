@extends('layouts.app')

@section('title', 'Detail Log Transaksi #' . $donasi->id_donasi)

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <a href="{{ route('admin.riwayat_donasi.index') }}" class="text-decoration-none text-secondary small fw-semibold d-inline-flex align-items-center mb-2">
            <i class="fas fa-arrow-left me-1.5"></i> Kembali ke Riwayat Induk
        </a>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="fw-bold text-dark m-0" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                    Rincian Transaksi Donasi #{{ $donasi->id_donasi }}
                </h1>
                <p class="text-muted small m-0">Daftar manifestasi berkas donasi yang diserahkan dan diverifikasi ke sistem.</p>
            </div>
            <div>
                @if($donasi->status_donasi === 'Selesai')
                    <span class="badge rounded-3 px-3 py-2 fw-bold" style="background-color: #d1fae5; color: #065f46; font-size: 0.85rem;">✓ Selesai</span>
                @elseif($donasi->status_donasi === 'Proses')
                    <span class="badge rounded-3 px-3 py-2 fw-bold" style="background-color: #eff6ff; color: #1e40af; font-size: 0.85rem;">⏳ Proses</span>
                @else
                    <span class="badge rounded-3 px-3 py-2 fw-bold" style="background-color: #fee2e2; color: #991b1b; font-size: 0.85rem;">✕ Ditolak</span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-emerald border-0 text-white shadow-sm rounded-3 mb-4 p-3 d-flex align-items-center" style="background-color: #059669;" role="alert">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div class="small fw-semibold">{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark m-0"><i class="fas fa-box-open text-secondary me-2"></i>Komoditas Donasi</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    @if($donasi->donasi_uang)
                        <div class="p-4 rounded-4 mb-3" style="background-color: #f0f9ff; border: 1px solid #bae6fd;">
                            <div class="text-secondary small text-uppercase tracking-wider font-semibold">Total Nominal Uang Tunai</div>
                            <div class="fw-bold text-dark py-1" style="font-size: 2.25rem;">
                                Rp {{ number_format($donasi->donasi_uang->nominal, 0, ',', '.') }}
                            </div>
                            <hr class="text-muted my-3" style="opacity: 0.15;">
                            <div class="row g-3">
                                <div class="col-6 col-sm-4">
                                    <div class="text-muted small">ID Saluran Bayar</div>
                                    <div class="fw-bold text-dark small">{{ $donasi->donasi_uang->pembayaran->id_pembayaran ?? 'Manual Admin' }}</div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="text-muted small">Metode Bayar</div>
                                    <div class="fw-bold text-dark small text-uppercase">{{ $donasi->donasi_uang->pembayaran->metode_pembayaran ?? 'Tunai' }}</div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="text-muted small">Status Gateway</div>
                                    <div class="fw-bold text-success small">SUCCESS</div>
                                </div>
                            </div>
                        </div>
                    @elseif($donasi->donasi_barang)
                        <div class="p-4 rounded-4 mb-3" style="background-color: #fffbeb; border: 1px solid #fef3c7;">
                            <div class="text-secondary small text-uppercase tracking-wider font-semibold">Logistik / Berwujud Barang</div>
                            <div class="fw-bold text-dark py-1" style="font-size: 1.75rem;">
                                {{ $donasi->donasi_barang->nama_barang }}
                            </div>
                            <div class="text-muted small">Kuantitas Pasokan: <span class="fw-bold text-dark">{{ $donasi->donasi_barang->jumlah }} Paket/Unit</span></div>
                            <hr class="text-muted my-3" style="opacity: 0.15;">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-muted small">Kategori Komoditas</div>
                                    <div class="fw-bold text-dark small">{{ $donasi->donasi_barang->kategori_barang->nama_kategori ?? 'Umum' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Deskripsi Penyaluran</div>
                                    <div class="fw-bold text-dark small">{{ $donasi->donasi_barang->keterangan ?? 'Tidak ada catatan tambahan' }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <h6 class="fw-bold text-dark mb-3">Informasi Berkas Kunjungan</h6>
                        <div class="row g-3 bg-light p-3 rounded-3 mx-0">
                            <div class="col-6 col-sm-4">
                                <span class="text-muted d-block small">ID Lembar Kunjungan</span>
                                <span class="fw-semibold text-dark small">#{{ $donasi->id_kunjungan }}</span>
                            </div>
                            <div class="col-6 col-sm-4">
                                <span class="text-muted d-block small">Tanggal Kedatangan</span>
                                <span class="fw-semibold text-dark small">{{ \Carbon\Carbon::parse($row->tgl_donasi ?? now())->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="col-12 col-sm-4">
                                <span class="text-muted d-block small">Skor Kepuasan SOWAN</span>
                                <span class="fw-semibold text-warning small">
                                    @if($donasi->kunjungan && $donasi->kunjungan->penilaian)
                                        <i class="fas fa-star me-1"></i>{{ $donasi->kunjungan->penilaian->skor_rating ?? '0' }} / 5
                                    @else
                                        <span class="text-muted">Belum mengisi rating</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center justify-content-between" style="background-color: #f8fafc; border-radius: 1rem;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 bg-white text-secondary shadow-sm me-3">
                            <i class="fas fa-user-shield fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted text-xs uppercase fw-bold tracking-wider" style="font-size: 0.7rem;">Otorisator Terakhir</div>
                            <div class="fw-bold text-dark small">{{ $donasi->user->nama_user ?? 'Sistem Otomatis / Belum Ada' }}</div>
                        </div>
                    </div>
                    <div class="text-end text-muted small">
                        Role Akses: <span class="badge bg-dark rounded-pill fw-semibold">{{ $donasi->user->role ?? 'None' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="far fa-user text-secondary me-2"></i>Profil Donatur</h5>
                    <div class="text-center py-3 bg-light rounded-4 mb-3">
                        <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center fw-bold mb-2" style="width: 56px; height: 56px; font-size: 1.5rem; background-color: #64748b !important;">
                            {{ strtoupper(substr($donasi->kunjungan->donatur->nama_donatur ?? 'A', 0, 1)) }}
                        </div>
                        <h6 class="fw-bold text-dark m-0">{{ $donasi->kunjungan->donatur->nama_donatur ?? 'Anonim / Umum' }}</h6>
                        <span class="text-muted text-xs">Entitas Pemberi Hibah</span>
                    </div>

                    <div class="mb-3 small">
                        <div class="text-muted">Nomor Kontak Handphone</div>
                        <div class="fw-semibold text-dark">{{ $donasi->kunjungan->donatur->no_hp ?? 'Tidak Mengisi' }}</div>
                    </div>
                    <div class="mb-4 small">
                        <div class="text-muted">Alamat Korespondensi</div>
                        <div class="fw-semibold text-dark">{{ $donasi->kunjungan->donatur->alamat ?? 'Tidak Mengisi' }}</div>
                    </div>

                    @if($donasi->kunjungan && $donasi->kunjungan->donatur && $donasi->kunjungan->donatur->no_hp)
                        @php
                            // Bersihkan nomor HP dari karakter non-numerik untuk API WhatsApp
                            $cleanPhone = preg_replace('/[^0-9]/', '', $donasi->kunjungan->donatur->no_hp);
                            // Sesuaikan awalan 0 menjadi kode negara 62
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $waMessage = urlencode("Halo Bapak/Ibu " . $donasi->kunjungan->donatur->nama_donatur . ", Kami dari pihak pengurus Yayasan ingin mengonfirmasi amanah donasi Anda dengan ID Transaksi #" . $donasi->id_donasi . " yang telah kami terima. Terima kasih.");
                        @endphp
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}" target="_blank" class="btn btn-success w-100 fw-bold shadow-sm py-2 rounded-3 text-white">
                            <i class="fab fa-whatsapp me-2 fs-5"></i>Hubungi via WhatsApp
                        </a>
                    @else
                        <button class="btn btn-light w-100 py-2 rounded-3 fw-bold border text-muted" disabled>
                            <i class="fab fa-whatsapp me-2"></i>Kontak Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-sliders-h text-secondary me-2"></i>Otorisasi Status</h5>
                    <form action="{{ route('admin.riwayat_donasi.update_status', $donasi->id_donasi) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Ubah Status Riwayat</label>
                            <select name="status_donasi" class="form-select bg-light small fw-bold">
                                <option value="Proses" {{ $donasi->status_donasi === 'Proses' ? 'selected' : '' }}>⏳ Proses</option>
                                <option value="Selesai" {{ $donasi->status_donasi === 'Selesai' ? 'selected' : '' }}>✓ Selesai</option>
                                <option value="Ditolak" {{ $donasi->status_donasi === 'Ditolak' ? 'selected' : '' }}>✕ Ditolak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn text-white w-100 fw-bold shadow-sm py-2 rounded-3" style="background-color: #047857;">
                            <i class="fas fa-save me-1.5"></i>Simpan Perubahan Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection