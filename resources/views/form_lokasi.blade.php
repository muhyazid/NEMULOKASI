@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Input Data Lokasi</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item active">Input Data Lokasi</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Lokasi</h3>
        </div>
        <div class="card-body">
            <p class="text-muted">Form ini digunakan untuk menilai kelayakan lokasi bisnis menggunakan metode Fuzzy Tsukamoto
            </p>

            <form action="{{ route('lokasi.hitung') }}" method="POST">
                @csrf

                <!-- Nama Lokasi -->
                <div class="form-group">
                    <label for="nama">Nama Lokasi:</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label for="alamat">Alamat:</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" required>
                </div>

                <!-- Aksesibilitas -->
                <div class="form-group">
                    <label for="aksesibilitas">Aksesibilitas:</label>
                    <select name="aksesibilitas" id="aksesibilitas" class="form-control" required>
                        <option value="" disabled selected>-- Silahkan pilih kategori linguistik parameter --</option>
                        <option value="sangat mudah">Sangat Mudah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tidak mudah">Tidak Mudah</option>
                    </select>
                    <small class="form-text text-muted">Kemudahan dalam mencapai lokasi</small>
                </div>

                <!-- Visibilitas -->
                <div class="form-group">
                    <label for="visibilitas">Visibilitas:</label>
                    <select name="visibilitas" id="visibilitas" class="form-control" required>
                        <option value="" disabled selected>-- Silahkan pilih kategori linguistik parameter --</option>
                        <option value="sangat terlihat">Sangat Terlihat</option>
                        <option value="terlihat sebagian">Terlihat Sebagian</option>
                        <option value="tidak terlihat">Tidak Terlihat</option>
                    </select>
                    <small class="form-text text-muted">Seberapa mudah bisnis terlihat dari jalan utama</small>
                </div>

                <!-- Daya Beli -->
                <div class="form-group">
                    <label for="daya_beli">Daya Beli:</label>
                    <select name="daya_beli" id="daya_beli" class="form-control" required>
                        <option value="" disabled selected>-- Silahkan pilih kategori linguistik parameter --</option>
                        <option value="tinggi">Tinggi</option>
                        <option value="menengah">Menengah</option>
                        <option value="rendah">Rendah</option>
                    </select>
                    <small class="form-text text-muted">Kemampuan masyarakat sekitar untuk membeli produk/jasa</small>
                </div>

                <!-- Persaingan -->
                <div class="form-group">
                    <label for="persaingan">Persaingan:</label>
                    <select name="persaingan" id="persaingan" class="form-control" required>
                        <option value="" disabled selected>-- Silahkan pilih kategori linguistik parameter --</option>
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                    <small class="form-text text-muted">Tingkat persaingan bisnis sejenis di area tersebut</small>
                </div>

                <!-- Infrastruktur -->
                <div class="form-group">
                    <label for="infrastruktur">Infrastruktur:</label>
                    <select name="infrastruktur" id="infrastruktur" class="form-control" required>
                        <option value="" disabled selected>-- Silahkan pilih kategori linguistik parameter --</option>
                        <option value="lengkap">Lengkap</option>
                        <option value="cukup">Cukup</option>
                        <option value="tidak lengkap">Tidak Lengkap</option>
                    </select>
                    <small class="form-text text-muted">Ketersediaan listrik, air, internet, dll</small>
                </div>

                <!-- Lingkungan Sekitar -->
                <div class="form-group">
                    <label for="lingkungan_sekitar">Lingkungan Sekitar:</label>
                    <select name="lingkungan_sekitar" id="lingkungan_sekitar" class="form-control" required>
                        <option value="" disabled selected>-- Silahkan pilih kategori linguistik parameter --</option>
                        <option value="sangat mendukung">Sangat Mendukung</option>
                        <option value="netral">Netral</option>
                        <option value="tidak mendukung">Tidak Mendukung</option>
                    </select>
                    <small class="form-text text-muted">Apakah lingkungan sekitar mendukung jenis bisnis ini</small>
                </div>

                <!-- Parkir -->
                <div class="form-group">
                    <label for="parkir">Parkir:</label>
                    <select name="parkir" id="parkir" class="form-control" required>
                        <option value="" disabled selected>-- Silahkan pilih kategori linguistik parameter --</option>
                        <option value="luas">Luas</option>
                        <option value="sedang">Sedang</option>
                        <option value="sempit">Sempit</option>
                    </select>
                    <small class="form-text text-muted">Ketersediaan lahan parkir untuk pelanggan</small>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Hitung Skor Lokasi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
