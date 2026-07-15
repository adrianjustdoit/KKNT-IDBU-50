@php
    $isHighlight = isset($highlight) && $highlight;
    $isSmall = isset($small) && $small;
    $cardWidth = $isHighlight ? '300px' : ($isSmall ? '200px' : '250px');
    $avatarSize = $isHighlight ? '140px' : ($isSmall ? '90px' : '120px');
    $nameSize = $isHighlight ? '1.4rem' : ($isSmall ? '1.1rem' : '1.25rem');
@endphp

<div class="org-card" style="background: white; border-radius: var(--radius-lg); padding: var(--space-xl); text-align: center; box-shadow: 0 10px 30px rgba(92, 74, 61, 0.08); width: {{ $cardWidth }}; transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative; {{ $isHighlight ? 'border: 2px solid var(--color-leaf);' : '' }}">
    
    @if($isHighlight)
        <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--color-leaf); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 0 4px 10px rgba(123, 160, 131, 0.4); white-space: nowrap;">
            Koordinator
        </div>
    @endif

    <div class="org-avatar" style="width: {{ $avatarSize }}; height: {{ $avatarSize }}; border-radius: 50%; background: var(--color-sand); margin: 0 auto var(--space-md); display: flex; align-items: center; justify-content: center; color: white; border: 4px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden;">
        @if($member->image_path)
            <img src="{{ asset('storage/' . $member->image_path) }}" alt="{{ $member->name }}" style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <span class="material-symbols-outlined" style="font-size: calc({{ $avatarSize }} * 0.4); color: var(--color-leaf);">person</span>
        @endif
    </div>
    
    <h3 style="font-size: {{ $nameSize }}; color: var(--color-wood); margin-bottom: 4px; font-weight: 700;">{{ $member->name }}</h3>
    <p style="color: var(--color-forest); font-weight: 500; font-size: 0.95rem;">
        {{ $member->role }}
        @if(in_array($member->role, ['Kadiv', 'Anggota']) && $member->division)
            <br><span style="font-size: 0.85rem; opacity: 0.8;">Divisi {{ $member->division }}</span>
        @endif
    </p>
</div>

<style>
    .org-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(92, 74, 61, 0.15);
    }
</style>
