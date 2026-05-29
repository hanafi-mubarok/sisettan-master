<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name') }} - Register</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
</head>

<body class="register-proto-page">
    <div id="app">
        <header class="register-proto-topbar">
            <div class="register-proto-topbar-inner">
                <img src="{{ asset('images/logo_midi.png') }}" alt="PT Midi Logo" class="register-proto-logo">
                <span class="register-proto-company">PT Midi Utama Indonesia Tbk</span>
            </div>
        </header>

        <section class="section register-proto-section">
            <div class="container register-proto-container">
                <div class="register-proto-card-wrap">
                    <div class="card register-proto-card">
                        <div class="card-body">
                            <!-- Modal Konfirmasi Karyawan -->
                            <div id="employeeConfirmationModal" class="modal fade" tabindex="-1" role="dialog" style="display: none; background: rgba(0,0,0,0.5);">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Status</h5>
                                        </div>
                                        <div class="modal-body">
                                            <p style="font-size: 16px; margin: 20px 0;">Apakah anda merupakan karyawan PT Midi Utama Indonesia Tbk?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" id="employeeNoBtn" data-value="no">Tidak</button>
                                            <button type="button" class="btn btn-primary" id="employeeYesBtn" data-value="yes">Ya</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h1 class="register-proto-title">Lengkapi Profil</h1>

                            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="register-proto-form" id="registrationForm">
                                @csrf
                                
                                <!-- Hidden field untuk is_karyawan -->
                                <input type="hidden" name="is_karyawan" id="is_karyawan" value="">

                                <!-- Bagian: Field Minimal (untuk karyawan) -->
                                <div id="minimalFieldsSection">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="nik">NIK</label>
                                        <input id="nik" type="text" name="nik" value="{{ old('nik') }}"
                                            class="form-control @error('nik') is-invalid @enderror" placeholder="Masukkan NIK" required>
                                        @error('nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan alamat email" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bagian: Field Tambahan (untuk non-karyawan) -->
                                <div id="additionalFieldsSection" style="display: none;">
                                    <div class="form-group">
                                        <label for="phone">No HP Aktif</label>
                                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                                            placeholder="Masukkan nomor HP aktif">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Alamat Lengkap</label>
                                        <textarea id="address" name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="province">Provinsi</label>
                                            <select id="province" name="province" class="form-control">
                                                <option value="">Pilih provinsi</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="city">Kota/Kabupaten</label>
                                            <select id="city" name="city" class="form-control" disabled>
                                                <option value="">Pilih kota/kabupaten</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="selfie_ktp" id="selfie_ktp_label">Upload Selfie</label>
                                            <input id="selfie_ktp" type="file" name="selfie_ktp" class="form-control-file register-proto-file">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="kartu_keluarga" id="kartu_keluarga_label">Upload KTP</label>
                                            <input id="kartu_keluarga" type="file" name="kartu_keluarga" class="form-control-file register-proto-file">
                                        </div>
                                    </div>
                                </div>

                                <div class="register-proto-account-title">Data Akun</div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password_confirmation">Konfirmasi Password</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password"
                                            class="form-control" placeholder="Masukkan konfirmasi password">
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="btn register-proto-submit btn-lg btn-block">
                                    Simpan &amp; Lanjutkan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="simple-footer register-proto-footer">
                    Copyright &copy; {{ date('Y') }} PT Midi Utama Indonesia Tbk
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="../assets/js/stisla.js"></script>

    <!-- JS Libraies -->
    <script src="../node_modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
    <script src="../node_modules/selectric/public/jquery.selectric.min.js"></script>

    <!-- Template JS File -->
    <script src="../assets/js/scripts.js"></script>
    <script src="../assets/js/custom.js"></script>

    <!-- Page Specific JS File -->
    <script src="../assets/js/page/auth-register.js"></script>

    <script>
        (function () {
            // ===== Employee Confirmation Modal Logic =====
            const employeeConfirmationModal = document.getElementById('employeeConfirmationModal');
            const employeeYesBtn = document.getElementById('employeeYesBtn');
            const employeeNoBtn = document.getElementById('employeeNoBtn');
            const isKaryawanInput = document.getElementById('is_karyawan');
            const minimalFieldsSection = document.getElementById('minimalFieldsSection');
            const additionalFieldsSection = document.getElementById('additionalFieldsSection');
            const phoneFieldGroup = document.getElementById('phone').closest('.form-group');
            const addressFieldGroup = document.getElementById('address').closest('.form-group');
            const regionFieldRow = document.getElementById('province').closest('.form-row');
            const selfieKtpLabel = document.getElementById('selfie_ktp_label');
            const kartuKeluargaLabel = document.getElementById('kartu_keluarga_label');
            const registrationForm = document.getElementById('registrationForm');

            // Show modal on page load if is_karyawan is not set
            window.addEventListener('load', function () {
                if (!isKaryawanInput.value) {
                    employeeConfirmationModal.style.display = 'flex';
                    employeeConfirmationModal.classList.add('show');
                    employeeConfirmationModal.setAttribute('aria-modal', 'true');
                } else {
                    updateFormVisibility();
                }
            });

            // Handle Yes button click
            employeeYesBtn.addEventListener('click', function () {
                isKaryawanInput.value = 'yes';
                employeeConfirmationModal.style.display = 'none';
                employeeConfirmationModal.classList.remove('show');
                employeeConfirmationModal.removeAttribute('aria-modal');
                updateFormVisibility();
            });

            // Handle No button click
            employeeNoBtn.addEventListener('click', function () {
                isKaryawanInput.value = 'no';
                employeeConfirmationModal.style.display = 'none';
                employeeConfirmationModal.classList.remove('show');
                employeeConfirmationModal.removeAttribute('aria-modal');
                updateFormVisibility();
            });

            // Update form visibility based on is_karyawan value
            function updateFormVisibility() {
                if (isKaryawanInput.value === 'yes') {
                    // Show minimal fields + upload fields for employee
                    minimalFieldsSection.style.display = 'block';
                    additionalFieldsSection.style.display = 'block';
                    phoneFieldGroup.style.display = 'none';
                    addressFieldGroup.style.display = 'none';
                    regionFieldRow.style.display = 'none';
                    selfieKtpLabel.textContent = 'Upload Selfie';
                    kartuKeluargaLabel.textContent = 'Upload ID Card Karyawan';
                    // Update NIK placeholder for employee
                    document.getElementById('nik').placeholder = 'Masukkan Nomor Induk Karyawan';
                } else if (isKaryawanInput.value === 'no') {
                    // Show all fields
                    minimalFieldsSection.style.display = 'block';
                    additionalFieldsSection.style.display = 'block';
                    phoneFieldGroup.style.display = 'block';
                    addressFieldGroup.style.display = 'block';
                    regionFieldRow.style.display = 'flex';
                    selfieKtpLabel.textContent = 'Upload Selfie';
                    kartuKeluargaLabel.textContent = 'Upload Foto KTP';
                    // Update NIK placeholder for non-employee
                    document.getElementById('nik').placeholder = 'Masukkan NIK';
                }
            }

            // ===== Province/City Dropdown Logic =====
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const oldProvince = @json(old('province'));
            const oldCity = @json(old('city'));

            if (!provinceSelect || !citySelect) {
                return;
            }

            const resetCity = function (placeholder) {
                citySelect.innerHTML = '';
                const option = document.createElement('option');
                option.value = '';
                option.textContent = placeholder || 'Pilih kota/kabupaten';
                citySelect.appendChild(option);
                citySelect.disabled = true;
            };

            const createOption = function (label, value, dataId) {
                const option = document.createElement('option');
                option.textContent = label;
                option.value = value;
                if (dataId) {
                    option.dataset.id = dataId;
                }
                return option;
            };

            const loadCities = async function (provinceId, selectedCityName) {
                if (!provinceId) {
                    resetCity('Pilih kota/kabupaten');
                    return;
                }

                resetCity('Memuat kota/kabupaten...');

                try {
                    const response = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + provinceId + '.json');
                    const cities = await response.json();

                    resetCity('Pilih kota/kabupaten');
                    citySelect.disabled = false;

                    cities.forEach(function (city) {
                        citySelect.appendChild(createOption(city.name, city.name, city.id));
                    });

                    if (selectedCityName) {
                        citySelect.value = selectedCityName;
                    }
                } catch (error) {
                    resetCity('Gagal memuat kota/kabupaten');
                }
            };

            const init = async function () {
                try {
                    const response = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                    const provinces = await response.json();

                    provinces.forEach(function (province) {
                        provinceSelect.appendChild(createOption(province.name, province.name, province.id));
                    });

                    if (oldProvince) {
                        provinceSelect.value = oldProvince;
                        const selected = provinceSelect.options[provinceSelect.selectedIndex];
                        await loadCities(selected ? selected.dataset.id : '', oldCity);
                    }
                } catch (error) {
                    const failOption = document.createElement('option');
                    failOption.value = '';
                    failOption.textContent = 'Gagal memuat provinsi';
                    provinceSelect.appendChild(failOption);
                }
            };

            provinceSelect.addEventListener('change', function () {
                const selected = provinceSelect.options[provinceSelect.selectedIndex];
                loadCities(selected ? selected.dataset.id : '', '');
            });

            init();
        })();
    </script>
</body>

</html>
