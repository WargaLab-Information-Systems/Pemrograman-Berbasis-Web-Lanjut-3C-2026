<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_beranda_mengarahkan_tamu_ke_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_halaman_login_dan_registrasi_tampil(): void
    {
        $this->get('/login')->assertOk()->assertSee('Login');
        $this->get('/register')->assertOk()->assertSee('Registrasi');
    }
}
