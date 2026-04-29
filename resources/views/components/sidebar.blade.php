@props(['division' => 'animal-bite'])

<div class="deznav modern-production-sidebar">
    <div class="deznav-scroll modern-sidebar-scroll">
        {{-- Show the animal bite sidebar --}}
        @include('components.sidebar.animal-bite')
        
        <div class="modern-sidebar-footer" style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px;">
            <p style="font-size: 12px; color: #fff; margin-bottom: 0;"><strong>Cebu ABC</strong><br>© 2026 All Rights Reserved</p>
        </div>
    </div>
</div>
