<?php
$judul = "Ini adalah Judul Dari Kelas Komponent"
?>
<x-halaman-layout :title="$judul ">
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Itaque praesentium maiores harum ut commodi rem sunt eum deserunt amet iusto similique tempore impedit vero consectetur hic laboriosam accusantium quae, incidunt possimus ab nam? Reprehenderit facilis, a quod velit facere quisquam nobis odio soluta corrupti cumque temporibus modi ipsum esse culpa?</p>

    <x-slot name="tanggal">17 agustus 1945</x-slot>
    <x-slot name="penulis">darmaputra</x-slot>
</x-halaman-layout>
