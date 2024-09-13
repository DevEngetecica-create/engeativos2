@foreach($notificacoes as $notificacao)
<div class="text-reset notification-item d-block dropdown-item position-relative">
    <div class="d-flex">
        
        <div class="flex-grow-1">
            <a href="{{ route('ferramental.retirada.detalhes', $notificacao->id_servico) }}" class="stretched-link">
                <h6 class="mt-0 mb-1 fs-13 fw-semibold">tipo</h6>
            </a>
            <div class="fs-13 text-muted">
                <p class="mb-1">{{ $notificacao->mensagem }}</p>
            </div>
            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                <span><i class="mdi mdi-clock-outline"></i> 48 min ago</span>
            </p>
        </div>
        <div class="px-2 fs-15">
            <div class="form-check notification-check">
                <input class="form-check-input" type="checkbox" value="" id="all-notification-check02">
                <label class="form-check-label" for="all-notification-check02"></label>
            </div>
        </div>
    </div>
</div>

@endforeach