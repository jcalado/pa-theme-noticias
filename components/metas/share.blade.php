<div class="d-flex justify-content-between">
    @php require(get_template_directory() . '/components/parts/share.php') @endphp

    <div class="pa-accessibility">
        <ul class="list-inline">
            <li class="pa-text-dec list-inline-item"><a href="#" class="rounded p-2" onclick="window.TextSize.pa_diminui_texto(event)">-A</a></li>
            <li class="pa-text-inc list-inline-item"><a href="#" class="rounded p-2" onclick="window.TextSize.pa_aumenta_texto(event)">+A</a></li>

            @if(get_post_meta(get_the_ID(), 'amazon_polly_enable', true))
                <li class="pa-text-listen list-inline-item">
                    <a href="#" class="rounded p-2" onclick="pa_play(event, this)">
                        <i class="fas fa-volume-up"></i> {{__('Hear text', 'iasd')}}
                    </a>

                    <audio id="pa-accessibility-player" src="{{ get_post_meta(get_the_ID(), 'amazon_polly_audio_link_location', true) }}" controls></audio>
                </li>
            @endif
        </ul>
    </div>
</div>
