@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>User List</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">User Management</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>User List</h4>
                            <div class="card-header-action">
                                <a class="btn btn-icon icon-left btn-primary" href="{{ route('user.create') }}">Create New
                                    User</a>
                                <a class="btn btn-icon icon-left btn-success" href="{{ route('user.export') }}">
                                    <i class="fa fa-file-excel"></i> Export Excel
                                </a>
                                <button type="button" class="btn btn-icon icon-left btn-warning" data-toggle="modal"
                                    data-target="#importModal">
                                    <i class="fa fa-file-upload"></i> Import Excel
                                </button>
                                <a class="btn btn-info btn-primary active search">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Search User</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('user.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="role">User</label>
                                            <input type="text" name="username" class="form-control" id="name"
                                                placeholder="User Name" value="{{ $user }}">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('user.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>No</th>
                                            <th>Email</th>
                                            <th>Nama</th>
                                            <th>No. HP</th>
                                            <th>Alamat</th>
                                            <th>Created At</th>
                                            <th>Role</th>
                                            <th>Verifikasi</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        @foreach ($users as $key => $user)
                                            @php
                                                $buildDocUrl = function ($path) {
                                                    if (!$path) {
                                                        return '';
                                                    }

                                                    $cleanPath = ltrim($path, '/');

                                                    if (\Illuminate\Support\Str::startsWith($cleanPath, ['http://', 'https://'])) {
                                                        return $cleanPath;
                                                    }

                                                    if (\Illuminate\Support\Str::startsWith($cleanPath, 'storage/')) {
                                                        return asset($cleanPath);
                                                    }

                                                    if (\Illuminate\Support\Str::startsWith($cleanPath, 'uploads/')) {
                                                        return asset('storage/' . $cleanPath);
                                                    }

                                                    return asset($cleanPath);
                                                };

                                                $selfieDocUrl = $buildDocUrl($user->selfie_ktp_path);
                                                $kkDocUrl = $buildDocUrl($user->kartu_keluarga_path);

                                                // Role hierarchy: super-admin (0) > admin_ho (1) > admin_branch (2) > user (3)
                                                $roleHierarchy = [
                                                    'super-admin' => 0,
                                                    'admin_ho' => 1,
                                                    'admin_branch' => 2,
                                                    'user' => 3,
                                                ];

                                                $loginUserRole = strtolower((string) (auth()->user()->role_name ?? 'user'));
                                                $targetUserRole = strtolower((string) ($user->role_name ?? 'user'));

                                                $loginUserLevel = $roleHierarchy[$loginUserRole] ?? 999;
                                                $targetUserLevel = $roleHierarchy[$targetUserRole] ?? 999;

                                                // Disable actions if login user is admin_branch and target user has higher role
                                                $canEditUser = $loginUserRole !== 'admin_branch' || $targetUserLevel >= $loginUserLevel;
                                            @endphp
                                            <tr>
                                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->address }}</td>
                                                <td>{{ $user->created_at }}</td>
                                                <td>{{ $user->role_name ?? '-' }}</td>
                                                <td>
                                                    @if ($user->isverified)
                                                        <span class="badge badge-success">Approved</span>
                                                    @else
                                                        <span class="badge badge-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        @if (!$user->isverified)
                                                            <button type="button" class="btn btn-sm btn-success btn-icon mr-2"
                                                                data-toggle="modal" data-target="#approveModal"
                                                                data-nik="{{ $user->nik }}"
                                                                data-name="{{ $user->name }}"
                                                                data-is-karyawan="{{ $user->is_karyawan ? 1 : 0 }}"
                                                                data-selfie="{{ $selfieDocUrl }}"
                                                                data-kk="{{ $kkDocUrl }}"
                                                                data-approve-url="{{ route('user.approve', $user->id) }}"
                                                                @if (!$canEditUser) disabled title="Anda tidak dapat approve user dengan role lebih tinggi" @endif>
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('user.edit', $user->id) }}"
                                                            class="btn btn-sm btn-info btn-icon @if (!$canEditUser) disabled @endif"
                                                            @if (!$canEditUser) onclick="event.preventDefault(); alert('Anda tidak dapat mengedit user dengan role lebih tinggi');" style="cursor: not-allowed; opacity: 0.6;" @endif>
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        @if ($user->deleted_at == null)
                                                            <form action="{{ route('user.destroy', $user->id) }}"
                                                                method="POST" class="ml-2" id="del-<?= $user->id ?>"
                                                                @if (!$canEditUser) onsubmit="event.preventDefault(); alert('Anda tidak dapat blacklist user dengan role lebih tinggi'); return false;" @endif>
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}">
                                                                <button type="submit" id="#submit"
                                                                    class="btn btn-sm btn-danger btn-icon @if (!$canEditUser) disabled @endif"
                                                                    @if (!$canEditUser) disabled title="Anda tidak dapat blacklist user dengan role lebih tinggi" @endif>
                                                                    <i class="fas fa-times"> </i> Blacklist </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('user.restore', $user->id) }}"
                                                                method="POST" class="ml-2" id="del-<?= $user->id ?>"
                                                                @if (!$canEditUser) onsubmit="event.preventDefault(); alert('Anda tidak dapat mengaktifkan user dengan role lebih tinggi'); return false;" @endif>
                                                                <input type="hidden" name="_method" value="PATCH">
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}">
                                                                <button type="submit" id="#submit"
                                                                    class="btn btn-sm btn-success btn-icon @if (!$canEditUser) disabled @endif"
                                                                    @if (!$canEditUser) disabled title="Anda tidak dapat mengaktifkan user dengan role lebih tinggi" @endif>
                                                                    <i class="fas fa-times"> </i> Active </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $users->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importModalLabel">Import Excel User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-info mb-3">
                                            Download template dulu, isi sesuai kolom di bawah, lalu upload file Excel yang sudah diisi.
                                            Kolom Created At dan Verifikasi akan diatur otomatis oleh sistem.
                                        </div>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Email</th>
                                                        <th>Nama</th>
                                                        <th>No. HP</th>
                                                        <th>Alamat</th>
                                                        <th>Role</th>
                                                        <th>Is Karyawan</th>
                                                        <th>Password</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>contoh@email.com</td>
                                                        <td>Nama User</td>
                                                        <td>08123456789</td>
                                                        <td>Alamat lengkap</td>
                                                        <td>user</td>
                                                        <td>0</td>
                                                        <td>Rahasia123</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3">
                                            <a href="{{ route('user.template') }}" class="btn btn-outline-primary">
                                                <i class="fa fa-download"></i> Download Template Excel
                                            </a>
                                        </div>
                                        <form action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="import_file">Upload File Excel</label>
                                                <input type="file" id="import_file"
                                                    class="form-control @error('import_file') is-invalid @enderror"
                                                    name="import_file" accept=".xlsx,.xls,.csv,.ods" required>
                                                @error('import_file')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Import File</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Preview Documents</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>NIK:</strong></p>
                                                <p id="approve-nik" class="mb-3">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Nama:</strong></p>
                                                <p id="approve-name" class="mb-0">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <p><strong>Selfie</strong></p>
                                <img id="approve-selfie-image" src="" alt="Selfie KTP" class="img-fluid img-thumbnail d-none">
                                <p id="approve-selfie-empty" class="text-muted mb-0">No file</p>
                            </div>
                            <div class="col-md-6 text-center">
                                <p><strong>KTP</strong></p>
                                <img id="approve-kk-image" src="" alt="Kartu Keluarga" class="img-fluid img-thumbnail d-none">
                                <p id="approve-kk-empty" class="text-muted mb-0">No file</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="#" method="POST" id="approve-form-global" class="w-100 d-flex flex-column align-items-end">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="role" id="approve-role-input" value="">
                            <button type="button" class="btn btn-danger mb-2 align-self-end"
                                data-dismiss="modal">Cancel</button>
                            <div id="approve-role-buttons" class="d-none d-flex flex-wrap justify-content-end"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('customScript')
    <script>
        $(document).ready(function() {
            // Keep approve modal outside stacking contexts from page containers.
            $('#approveModal').appendTo('body');
            $('#importModal').appendTo('body');

            // Populate modal from click event to avoid relatedTarget inconsistencies.
            $(document).on('click', 'button[data-target="#approveModal"]', function(e) {
                var btn = $(this);
                var nik = btn.attr('data-nik') || '-';
                var name = btn.attr('data-name') || '-';
                var isKaryawan = String(btn.attr('data-is-karyawan') || '0') === '1';
                var selfie = btn.attr('data-selfie') || '';
                var kk = btn.attr('data-kk') || '';
                var approveUrl = btn.attr('data-approve-url') || '#';
                var roleButtons = isKaryawan
                    ? [
                        { label: 'Approve as Super Admin', role: 'super-admin', className: 'btn-primary' },
                        { label: 'Approve as Admin HO', role: 'admin_ho', className: 'btn-info' },
                        { label: 'Approve as Admin Branch', role: 'admin_branch', className: 'btn-success' },
                        { label: 'Approve as User', role: 'user', className: 'btn-secondary' }
                    ]
                    : [
                        { label: 'Approve as User', role: 'user', className: 'btn-success' }
                    ];

                if (window.console && console.log) {
                    console.log('Approve button clicked:', { nik: nik, name: name, approveUrl: approveUrl, isKaryawan: isKaryawan });
                }

                $('#approve-form-global').attr('action', approveUrl);
                $('#approve-nik').text(nik);
                $('#approve-name').text(name);
                $('#approve-role-input').val('');

                var buttonsHtml = '';
                roleButtons.forEach(function(item) {
                    buttonsHtml += '<button type="button" class="btn ' + item.className + ' mr-2 mb-2 approve-role-btn" data-role="' + item.role + '">' + item.label + '</button>';
                });

                $('#approve-role-buttons').removeClass('d-none').html(buttonsHtml);

                if (selfie) {
                    $('#approve-selfie-image').attr('src', selfie).removeClass('d-none');
                    $('#approve-selfie-empty').addClass('d-none');
                } else {
                    $('#approve-selfie-image').attr('src', '').addClass('d-none');
                    $('#approve-selfie-empty').removeClass('d-none');
                }

                if (kk) {
                    $('#approve-kk-image').attr('src', kk).removeClass('d-none');
                    $('#approve-kk-empty').addClass('d-none');
                } else {
                    $('#approve-kk-image').attr('src', '').addClass('d-none');
                    $('#approve-kk-empty').removeClass('d-none');
                }
            });

            $(document).on('click', '.approve-role-btn', function() {
                var role = $(this).attr('data-role') || '';
                $('#approve-role-input').val(role);
                $('#approve-form-global').submit();
            });

            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
            });

            $('#importModal').on('show.bs.modal', function() {
                $('.navbar .form-inline .search-backdrop').css({
                    opacity: 0,
                    visibility: 'hidden'
                });
            });

            $('#approveModal').on('show.bs.modal', function(event) {
                // Ensure navbar search overlay never covers the modal.
                $('.navbar .form-inline .search-backdrop').css({
                    opacity: 0,
                    visibility: 'hidden'
                });

                // If there's no relatedTarget (e.g. modal opened programmatically),
                // don't override values populated by the click handler.
                if (!event.relatedTarget) {
                    return;
                }

                var button = $(event.relatedTarget);
                var nik = button.attr('data-nik') || '-';
                var name = button.attr('data-name') || '-';
                var selfie = button.attr('data-selfie') || '';
                var kk = button.attr('data-kk') || '';
                var approveUrl = button.attr('data-approve-url') || '#';

                $('#approve-form-global').attr('action', approveUrl);
                $('#approve-nik').text(nik);
                $('#approve-name').text(name);

                if (selfie) {
                    $('#approve-selfie-image').attr('src', selfie).removeClass('d-none');
                    $('#approve-selfie-empty').addClass('d-none');
                } else {
                    $('#approve-selfie-image').attr('src', '').addClass('d-none');
                    $('#approve-selfie-empty').removeClass('d-none');
                }

                if (kk) {
                    $('#approve-kk-image').attr('src', kk).removeClass('d-none');
                    $('#approve-kk-empty').addClass('d-none');
                } else {
                    $('#approve-kk-image').attr('src', '').addClass('d-none');
                    $('#approve-kk-empty').removeClass('d-none');
                }
            });
        });

        function submitDel(id) {
            // Hide any visible Bootstrap modals first
            $('.modal.show').modal('hide');

            // Give Bootstrap a moment to hide the modal, then force-remove any
            // lingering backdrop and modal-open class before submitting the form.
            setTimeout(function() {
                try {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                } catch (e) {
                    if (window.console && console.error) console.error('Backdrop cleanup failed', e);
                }

                $('#del-' + id).submit();
            }, 150);
        }

        function submitApprove(id) {
            $('#approve-' + id).submit()
        }
    </script>
@endpush

@push('customStyle')
    <style>
        /* Stisla search-backdrop uses z-index 9000; keep modal above it. */
        #importModal {
            z-index: 10055;
        }

        #approveModal {
            z-index: 10050;
        }

        .modal-backdrop {
            z-index: 10040;
        }
    </style>
@endpush
