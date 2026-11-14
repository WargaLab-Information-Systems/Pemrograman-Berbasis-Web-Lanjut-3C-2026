@extends('layouts.app')

@section('title', 'Kelola Pengguna | KATAMITHA')

@section('content')

    <section class="user-management-page">

        <div class="user-management-heading">

            <span class="user-management-label">
                MANAJEMEN PENGGUNA
            </span>

            <h2>
                Kelola Pengguna
            </h2>

            <p>
                Lihat data pengguna dan tetapkan role pengguna sesuai kewenangannya.
            </p>

        </div>


        @if (session('success'))

            <div class="user-management-success">
                {{ session('success') }}
            </div>

        @endif


        <div class="user-management-card">

            <div class="user-management-card-header">

                <div>
                    <h3>
                        Daftar Pengguna
                    </h3>

                    <p>
                        Total {{ $users->count() }} pengguna terdaftar.
                    </p>
                </div>

            </div>


            <div class="user-management-table-wrapper">

                <table class="user-management-table">

                    <thead>

                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role Saat Ini</th>

                            @can('kelola role pengguna')
                                <th>Penetapan Role</th>
                            @endcan
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($users as $user)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <div class="user-management-name">

                                        <span class="user-management-avatar">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>

                                        <div>
                                            <strong>
                                                {{ $user->name }}
                                            </strong>

                                            @if (auth()->id() === $user->id)
                                                <span class="user-management-self">
                                                    Akun Anda
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>

                                    @php
                                        $currentRole = $user->getRoleNames()->first();
                                    @endphp

                                    @if ($currentRole)

                                        <span class="user-role-badge">
                                            {{ ucfirst($currentRole) }}
                                        </span>

                                    @else

                                        <span class="user-role-empty">
                                            Belum memiliki role
                                        </span>

                                    @endif

                                </td>


                                @can('kelola role pengguna')

                                    <td>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.update-role', $user) }}"
                                            class="user-role-form"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <select
                                                name="role"
                                                class="user-role-select"
                                                required
                                            >

                                                @foreach ($roles as $role)

                                                    <option
                                                        value="{{ $role->name }}"
                                                        @selected($user->hasRole($role->name))
                                                    >
                                                        {{ ucfirst($role->name) }}
                                                    </option>

                                                @endforeach

                                            </select>

                                            <button
                                                type="submit"
                                                class="user-role-button"
                                            >
                                                Simpan Role
                                            </button>

                                        </form>

                                    </td>

                                @endcan

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="user-management-empty"
                                >
                                    Belum ada pengguna yang terdaftar.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="user-management-back">

            <a href="{{ route('admin.dashboard') }}">
                Kembali ke Dashboard Admin
            </a>

        </div>

    </section>

@endsection