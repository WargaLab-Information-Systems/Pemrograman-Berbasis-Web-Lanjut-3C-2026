<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        // Hasilnya: ['Fiksi' => 1, 'Nonfiksi' => 2, 'Pengembangan Diri' => 3]
        $kategori = Kategori::pluck('id', 'nama');

        // Format: [nama kategori, judul, penulis, tahun terbit]
        $data = [
            // ===== Pengembangan Diri =====
            ['Pengembangan Diri', 'Filosofi Teras', 'Henry Manampiring', 2018],
            ['Pengembangan Diri', 'The Subtle Art of Not Giving a F*ck', 'Mark Manson', 2016],
            ['Pengembangan Diri', 'Ikigai: The Japanese Secret to a Long and Happy Life', 'Héctor García dan Francesc Miralles', 2016],
            ['Pengembangan Diri', 'The Courage to be Disliked', 'Ichiro Kishimi dan Fumitake Koga', 2013],
            ['Pengembangan Diri', 'Atomic Habits', 'James Clear', 2018],
            ['Pengembangan Diri', 'The 7 Habits of Highly Effective People', 'Stephen R. Covey', 1989],
            ['Pengembangan Diri', 'How to Win Friends and Influence People', 'Dale Carnegie', 1936],
            ['Pengembangan Diri', 'Rich Dad Poor Dad', 'Robert T. Kiyosaki', 1997],
            ['Pengembangan Diri', 'Mindset: The New Psychology of Success', 'Carol S. Dweck', 2006],
            ['Pengembangan Diri', 'Deep Work', 'Cal Newport', 2016],
            ['Pengembangan Diri', "Man's Search for Meaning", 'Viktor E. Frankl', 1946],
            ['Pengembangan Diri', 'Think and Grow Rich', 'Napoleon Hill', 1937],

            // ===== Fiksi =====
            ['Fiksi', 'Edensor', 'Andrea Hirata', 2007],
            ['Fiksi', 'Laskar Pelangi', 'Andrea Hirata', 2005],
            ['Fiksi', 'Sang Pemimpi', 'Andrea Hirata', 2006],
            ['Fiksi', 'Bumi Manusia', 'Pramoedya Ananta Toer', 1980],
            ['Fiksi', 'Negeri 5 Menara', 'Ahmad Fuadi', 2009],
            ['Fiksi', 'Laut Bercerita', 'Leila S. Chudori', 2017],
            ['Fiksi', 'Pulang', 'Tere Liye', 2015],
            ['Fiksi', 'Cantik Itu Luka', 'Eka Kurniawan', 2002],
            ['Fiksi', 'Dilan 1990', 'Pidi Baiq', 2014],
            ['Fiksi', 'Ayat-Ayat Cinta', 'Habiburrahman El Shirazy', 2004],
            ['Fiksi', 'Perahu Kertas', 'Dee Lestari', 2009],
            ['Fiksi', "Harry Potter and the Philosopher's Stone", 'J.K. Rowling', 1997],
            ['Fiksi', 'The Hobbit', 'J.R.R. Tolkien', 1937],
            ['Fiksi', '1984', 'George Orwell', 1949],
            ['Fiksi', 'The Little Prince', 'Antoine de Saint-Exupéry', 1943],

            // ===== Nonfiksi =====
            ['Nonfiksi', 'A Brief History of Time', 'Stephen Hawking', 1988],
            ['Nonfiksi', 'Cosmos', 'Carl Sagan', 1980],
            ['Nonfiksi', 'Guns, Germs, and Steel', 'Jared Diamond', 1997],
            ['Nonfiksi', 'Clean Code', 'Robert C. Martin', 2008],
            ['Nonfiksi', 'The Pragmatic Programmer', 'Andrew Hunt dan David Thomas', 1999],
            ['Nonfiksi', 'Design Patterns', 'Erich Gamma, Richard Helm, Ralph Johnson, dan John Vlissides', 1994],
            ['Nonfiksi', 'Laravel: Up & Running', 'Matt Stauffer', 2016],
            ['Nonfiksi', 'Eloquent JavaScript', 'Marijn Haverbeke', 2011],
            ['Nonfiksi', 'Catatan Seorang Demonstran', 'Soe Hok Gie', 1983],
        ];

        foreach ($data as [$namaKategori, $judul, $penulis, $tahun]) {
            Buku::factory()->create([
                'kategori_id'  => $kategori[$namaKategori],
                'judul'        => $judul,
                'penulis'      => $penulis,
                'tahun_terbit' => $tahun,
            ]);
        }
    }
}