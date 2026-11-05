<section class="profile-form-content">

    {{-- =====================================================
         FORM INFORMASI PROFIL
         ===================================================== --}}
    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>


    <form
        method="post"
        action="{{ route('profile.update') }}"
        class="katamitha-profile-form"
    >

        @csrf
        @method('patch')


        {{-- NAMA --}}
        <div class="form-group form-group-full">

            <label for="name">
                Nama
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                placeholder="Masukkan nama"
                required
                autofocus
                autocomplete="name"
            >

            @if ($errors->has('name'))

                <div class="profile-field-error">

                    @foreach ($errors->get('name') as $error)

                        <p>
                            {{ $error }}
                        </p>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- EMAIL --}}
        <div class="form-group form-group-full">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                placeholder="Masukkan alamat email"
                required
                autocomplete="username"
            >

            @if ($errors->has('email'))

                <div class="profile-field-error">

                    @foreach ($errors->get('email') as $error)

                        <p>
                            {{ $error }}
                        </p>

                    @endforeach

                </div>

            @endif


            {{-- VERIFIKASI EMAIL --}}
            @if (
                $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail
                && ! $user->hasVerifiedEmail()
            )

                <div class="profile-verification">

                    <p>
                        Alamat email kamu belum diverifikasi.
                    </p>

                    <button
                        type="submit"
                        form="send-verification"
                        class="profile-verification-button"
                    >
                        Kirim ulang email verifikasi
                    </button>

                </div>


                @if (session('status') === 'verification-link-sent')

                    <p class="success-message">
                        Link verifikasi baru telah dikirim
                        ke alamat email kamu.
                    </p>

                @endif

            @endif

        </div>


        {{-- TOMBOL SIMPAN --}}
        <div class="form-actions">

            <button
                type="submit"
                class="detail-button"
            >
                Simpan Perubahan
            </button>

        </div>


        {{-- PESAN BERHASIL --}}
        @if (session('status') === 'profile-updated')

            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="success-message"
            >
                Profil berhasil diperbarui.
            </p>

        @endif

    </form>

</section>