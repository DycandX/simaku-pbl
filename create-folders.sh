#!/bin/bash

# Daftar folder
folders=(
"resources/views/auth/login"
"resources/views/auth/logout"
"resources/views/admin"
"resources/views/admin/dashboard"
"resources/views/admin/dashboard/history-log"
"resources/views/admin/dashboard/kelola-menu"
"resources/views/admin/dashboard/kelola-pengguna"
"resources/views/admin/dashboard/kelola-role"
"resources/views/staff-keuangan"
"resources/views/staff-keuangan/beasiswa"
"resources/views/staff-keuangan/dashboard"
"resources/views/staff-keuangan/dashboard/buat-tagihan-ukt"
"resources/views/staff-keuangan/dashboard/cek-tagihan-ukt"
"resources/views/staff-keuangan/dashboard/pembuatan-ukt"
"resources/views/staff-keuangan/dashboard/pengajuan-cicilan"
"resources/views/staff-keuangan/data-mahasiswa"
"resources/views/staff-keuangan/profile"
"resources/views/mahasiswa/beasiswa"
"resources/views/mahasiswa/dashboard"
"resources/views/mahasiswa/dashboard/daful-ukt"
"resources/views/mahasiswa/dashboard/tagihan-ukt"
"resources/views/mahasiswa/profile"
)

# Buat semua folder dan tambahkan .gitkeep
for folder in "${folders[@]}"; do
    mkdir -p "$folder"
    touch "$folder/.gitkeep"
done

echo "Semua folder berhasil dibuat dan .gitkeep ditambahkan."
