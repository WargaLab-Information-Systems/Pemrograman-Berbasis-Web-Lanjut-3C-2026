<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BukuCard extends Component
{
    /**
     * @param array{id: int, judul: string, penulis: string, tahun_terbit: int, kategori: string} $buku
     */
    public function __construct(public array $buku)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.buku-card');
    }
}
