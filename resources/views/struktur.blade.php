@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="org-hero" style="background: linear-gradient(135deg, var(--color-emerald) 0%, var(--color-forest-dark) 100%); padding: 140px 0 80px; text-align: center; position: relative; overflow: hidden;">
    <!-- Dekorasi background -->
    <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(20px);"></div>
    <div style="position: absolute; bottom: -80px; right: 10%; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; filter: blur(30px);"></div>
    
    <div class="container" style="position: relative; z-index: 1;" data-aos="fade-up">
        <h1 style="font-size: 3rem; margin-bottom: var(--space-md); font-weight: 700; color: #ffffff; text-shadow: 0 4px 12px rgba(0,0,0,0.15);">Struktur Organisasi</h1>
        <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.9); max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Susunan Dosen Pembimbing Lapangan dan Mahasiswa KKN-T IDBU 50 Universitas Diponegoro di Kelurahan Rowosari.
        </p>
    </div>
</div>

<section class="struktur-section" style="padding: var(--space-2xl) 0; background: var(--color-sand); min-height: 50vh;">
    <div class="container">
        
        @if($dpl->isEmpty() && $bph->isEmpty() && $divisions->isEmpty())
            <div style="text-align: center; padding: 100px 20px;" data-aos="fade-up">
                <span class="material-symbols-outlined" style="font-size: 4rem; color: var(--color-leaf); margin-bottom: 20px;">groups</span>
                <h3 style="color: var(--color-wood);">Struktur belum disusun</h3>
                <p style="color: var(--color-earth);">Admin belum menambahkan anggota ke dalam struktur organisasi.</p>
            </div>
        @endif

        {{-- DPL Section --}}
        @if($dpl->isNotEmpty())
            <div class="org-group" data-aos="fade-up" style="margin-bottom: var(--space-2xl);">
                <div class="org-group-title" style="text-align: center; margin-bottom: var(--space-xl);">
                    <h2 style="color: var(--color-forest); display: inline-block; padding-bottom: 8px; border-bottom: 3px solid var(--color-leaf);">Dosen Pembimbing Lapangan</h2>
                </div>
                <div class="org-chart" style="display: flex; flex-wrap: wrap; justify-content: center; gap: var(--space-xl);">
                    @foreach($dpl as $member)
                        @include('partials.org-card', ['member' => $member])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- BPH Section --}}
        @if($bph->isNotEmpty())
            <div class="org-group" data-aos="fade-up" style="margin-bottom: var(--space-2xl);">
                <div class="org-group-title" style="text-align: center; margin-bottom: var(--space-xl);">
                    <h2 style="color: var(--color-forest); display: inline-block; padding-bottom: 8px; border-bottom: 3px solid var(--color-leaf);">Badan Pengurus Harian (BPH)</h2>
                </div>
                
                {{-- Koordes di tengah atas --}}
                <div class="org-chart" style="display: flex; flex-wrap: wrap; justify-content: center; gap: var(--space-xl); margin-bottom: var(--space-xl);">
                    @foreach($bph->where('role', 'Koordes') as $member)
                        @include('partials.org-card', ['member' => $member, 'highlight' => true])
                    @endforeach
                </div>

                {{-- Wakoordes, Sekre, Bendum di bawahnya --}}
                <div class="org-chart" style="display: flex; flex-wrap: wrap; justify-content: center; gap: var(--space-xl);">
                    @foreach($bph->where('role', '!=', 'Koordes') as $member)
                        @include('partials.org-card', ['member' => $member])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Divisions Section --}}
        @if($divisions->isNotEmpty())
            <div class="org-group" data-aos="fade-up">
                <div class="org-group-title" style="text-align: center; margin-bottom: var(--space-xl);">
                    <h2 style="color: var(--color-forest); display: inline-block; padding-bottom: 8px; border-bottom: 3px solid var(--color-leaf);">Divisi Program Kerja</h2>
                </div>
                
                <div class="division-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--space-xl);">
                    @foreach(App\Models\Member::$divisions as $divName)
                        @if(isset($divisions[$divName]))
                            <div class="division-card" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.5); border-radius: var(--radius-xl); padding: var(--space-xl); text-align: center; box-shadow: 0 10px 30px rgba(92, 74, 61, 0.05); transition: transform 0.3s ease;">
                                <h3 style="color: var(--color-forest); margin-bottom: var(--space-lg); padding-bottom: 10px; border-bottom: 1px solid rgba(0,0,0,0.1);">Divisi {{ $divName }}</h3>
                                
                                {{-- Kadiv --}}
                                <div style="display: flex; flex-direction: column; gap: var(--space-md); align-items: center; margin-bottom: var(--space-lg);">
                                    @foreach($divisions[$divName]->where('role', 'Kadiv') as $member)
                                        @include('partials.org-card', ['member' => $member, 'small' => true])
                                    @endforeach
                                </div>

                                {{-- Anggota --}}
                                <div style="display: flex; flex-wrap: wrap; gap: var(--space-sm); justify-content: center;">
                                    @foreach($divisions[$divName]->where('role', 'Anggota') as $member)
                                        <div class="anggota-chip" style="background: white; padding: 6px 12px; border-radius: 20px; font-size: 0.9rem; color: var(--color-wood); box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 8px;">
                                            @if($member->image_path)
                                                <img src="{{ asset('storage/' . $member->image_path) }}" alt="{{ $member->name }}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                            @else
                                                <span class="material-symbols-outlined" style="font-size: 1.2rem; color: var(--color-leaf);">person</span>
                                            @endif
                                            {{ $member->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

<style>
    .division-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(92, 74, 61, 0.1);
    }
</style>
@endsection
