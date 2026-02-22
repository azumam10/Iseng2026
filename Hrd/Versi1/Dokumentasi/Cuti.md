# 📘 Dokumentasi Pengembangan Fitur Cuti (Leave Management) — HRIS Laravel + Filament

---

## 🎯 Tujuan Fitur

Membangun **fitur pengajuan cuti karyawan** pada aplikasi HRIS menggunakan:

* Laravel 12
* Filament v3
* Spatie Permission
* MySQL Database

Fitur ini memungkinkan:

* Karyawan mengajukan cuti
* HR melakukan approval
* Sistem menghitung jatah cuti otomatis
* Data tersimpan aman dan valid

---

# 🧱 1. Pembuatan Struktur Database

## ✅ Migration `leaves`

Tabel `leaves` dibuat sebagai pusat data cuti.

### Field yang dibuat

| Field         | Fungsi                        |
| ------------- | ----------------------------- |
| id            | Primary key                   |
| pegawai_id    | Relasi ke karyawan            |
| leave_type_id | Jenis cuti                    |
| start_date    | Tanggal mulai                 |
| end_date      | Tanggal selesai               |
| total_days    | Total hari cuti               |
| document_path | Upload surat PDF              |
| status        | pending / approved / rejected |
| hr_note       | Catatan HR                    |
| approved_by   | User HR yang approve          |
| approved_at   | Waktu approval                |
| cancelled_at  | Jika cuti dibatalkan          |
| timestamps    | created_at & updated_at       |

### Relasi Database

* `pegawai_id → pegawais`
* `leave_type_id → leave_types`
* `approved_by → users`

---

# 🧠 2. Pembuatan Model `Leave`

File:

```
app/Models/Leave.php
```

## Perubahan yang Dilakukan

### ✅ Fillable Field

Menentukan field yang boleh diisi mass assignment.

```php
protected $fillable = [
    'pegawai_id',
    'leave_type_id',
    'start_date',
    'end_date',
    'total_days',
    'document_path',
    'status',
    'hr_note',
    'approved_by',
    'approved_at',
    'cancelled_at',
];
```

---

### ✅ Casting Date

Agar otomatis menjadi object datetime.

```php
protected $casts = [
    'approved_at' => 'datetime',
    'cancelled_at' => 'datetime',
];
```

---

### ✅ Relasi Model

#### Pegawai

```php
return $this->belongsTo(Pegawai::class);
```

#### Jenis Cuti

```php
return $this->belongsTo(LeaveType::class);
```

#### Approver HR

```php
return $this->belongsTo(User::class,'approved_by');
```

---

### ✅ Method Baru: Hitung Cuti Terpakai

```php
public static function usedLeaveDays($pegawaiId,$year)
```

Fungsi:

* Menghitung total cuti **approved**
* Dalam tahun berjalan
* Yang belum dibatalkan

Digunakan untuk validasi jatah cuti tahunan.

---

# 🎛️ 3. Pembuatan Filament Resource

Command yang digunakan:

```bash
php artisan make:filament-resource Leave --generate
```

Resource otomatis membuat:

* Form
* Table
* Create Page
* Edit Page
* List Page

---

# 🧾 4. Form Pengajuan Cuti

File:

```
LeaveResource.php
```

## Komponen Form

### Pilih Pegawai

```php
Select::make('pegawai_id')
```

### Jenis Cuti

```php
Select::make('leave_type_id')
```

### Tanggal Cuti

* start_date
* end_date

Reactive → menghitung total hari otomatis.

---

### Upload Dokumen

```php
FileUpload::make('document_path')
```

✔ hanya menerima PDF.

---

### Catatan HR

```php
->visible(fn()=>auth()->user()->can('approve-cuti'))
```

➡ hanya HR yang melihat.

---

# 🚨 5. BUG YANG DITEMUKAN

## Error Database

```
Field 'total_days' doesn't have a default value
```

---

## Penyebab

Field:

```php
->disabled()
```

Pada HTML:

```
disabled input TIDAK ikut submit form
```

Akibatnya:

* Filament tidak mengirim `total_days`
* Database reject insert

---

## ✅ Solusi yang Dilakukan

### Tambah:

```php
->dehydrated()
```

atau

### SOLUSI LEVEL PROFESIONAL (dipilih)

Menghitung `total_days` di SERVER.

---

# 🧮 6. Auto Calculate Total Days (PERUBAHAN TERPENTING)

File:

```
CreateLeave.php
```

Ditambahkan:

```php
protected function mutateFormDataBeforeCreate(array $data): array
{
    $start = Carbon::parse($data['start_date']);
    $end = Carbon::parse($data['end_date']);

    $data['total_days'] = $start->diffInDays($end) + 1;

    return $data;
}
```

---

## Kenapa Ini Penting?

Jika dihitung di frontend:

User bisa edit request manual:

```
total_days = 999
```

➡ HRIS bisa dimanipulasi.

Dengan server calculation:

✅ Aman
✅ Valid
✅ Tidak bisa dimanipulasi

---

# 🛑 7. Validasi Jatah Cuti Tahunan

Ditambahkan pada:

```
CreateLeave.php → beforeCreate()
```

Logic:

1. Ambil total cuti approved tahun ini
2. Tambahkan pengajuan baru
3. Jika > 10 hari → ditolak

```php
if(($used + $this->data['total_days']) > 10){
    Notification::make()
        ->title('Sisa cuti tidak cukup')
        ->danger()
        ->send();

    $this->halt();
}
```

---

# 🔐 8. Implementasi Spatie Permission

Seeder dibuat:

```
RolePermissionSeeder.php
```

### Permission

* view-pegawai
* create-pegawai
* edit-pegawai
* delete-pegawai
* approve-cuti
* view-payslip

---

### Role

#### Admin

Semua akses.

#### Manager

* approve-cuti
* view-pegawai

#### Karyawan

* hanya melihat data.

---

## Fungsi Spatie Permission

Mengontrol:

* siapa boleh approve
* siapa lihat fitur HR
* siapa edit data

➡ Sistem siap multi-role di masa depan.

---

# ✅ 9. Status Sistem Saat Ini

## Sudah Berhasil

✔ Database Leave
✔ Model & Relasi
✔ Filament Resource
✔ Upload Dokumen PDF
✔ Hitung otomatis total cuti
✔ Validasi jatah 10 hari/tahun
✔ Role & Permission
✔ HR Approval Concept

---

# 🚀 Arsitektur Fitur Saat Ini

```
User Submit Cuti
        ↓
Filament Form
        ↓
Server Hitung total_days
        ↓
Validasi Jatah Tahunan
        ↓
Status = Pending
        ↓
HR Approve / Reject
```

---

# 🔥 Next Development (Direkomendasikan)

* Approval Action Button
* Reject wajib alasan
* Cancel sebelum approve
* Cuti tidak boleh overlap
* Exclude weekend
* Kalender cuti
* Sisa cuti realtime dashboard

---

## 🎓 Catatan Pembelajaran

Hal penting yang dipelajari:

1. Disabled field tidak dikirim ke server
2. Logic bisnis harus di backend
3. Filament memakai konsep dehydration
4. HRIS = business rule > CRUD
5. Permission system wajib sejak awal

---

✅ **Fitur Cuti HRIS versi Production Foundation telah selesai dibuat.**
